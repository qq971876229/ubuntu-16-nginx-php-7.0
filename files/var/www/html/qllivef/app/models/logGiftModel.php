<?php

namespace App\Models;

use bcl\redis\cacheBase;
use App\Domain\usersDomain;
use App\Domain\GiftDomain;


class logGiftModel extends baseModel
{


    public function getSource()
    {
        return "log_gift";
    }


    private $uid_;
    private $live_id_;
    private $path_get_;
    private $path_give_;


    public function set_uid($uid, $live_id)
    {

        $this->uid_ = $uid;
        $this->live_id_ = $live_id;

        $this->path_get_ = "users:gift:get:".$this->live_id_;
        $this->path_give_ = "users:gift:give:".$this->uid_;

    }

    public function add($money, $remark, $add_time, $session_id, $gift_id)
    {

        $this->update_value($money);

        $uid = $this->uid_;
        $live_id = $this->live_id_;

        $gift = new logGiftModel();

        $gift->uid = $uid;
        $gift->live_id = $live_id;
        $gift->money = $money;
        $gift->remark = $remark;
        $gift->add_time = $add_time;
        $gift->session_id = $session_id;
        $gift->gift_id = $gift_id;
        $gift->save();


        $path = "session:gift:give:".$session_id;

        \bcl\redis\base::get_rd()->del($path);

        return $gift;
    }


    public function commit()
    {
        \bcl\redis\base::get_rd()->expire($this->path_get_, 60 * 10);
        \bcl\redis\base::get_rd()->expire($this->path_give_, 60 * 10);
    }

    public function rollback()
    {

        if ($this->original_get_value_ == null) {
            return;
        }

        \bcl\redis\base::get_rd()->
        set($this->path_get_, $this->original_get_value_, 10);//10秒有效

        \bcl\redis\base::get_rd()->
        set($this->path_give_, $this->original_give_value_, 10);//10秒有效


    }


    private $original_get_value_ = null;
    private $original_give_value_ = null;

    private function update_value($uubean)
    {
        $get_uubean = self::get($this->live_id_) + $uubean;

        $give_uubean = self::give($this->uid_) + $uubean;

        if ($this->original_get_value_ == null) {
            $this->original_get_value_ = self::get($this->live_id_);
        }

        if ($this->original_give_value_ == null) {
            $this->original_give_value_ = self::give($this->uid_);
        }


        \bcl\redis\base::get_rd()->set($this->path_get_, $get_uubean, 10);//10秒有效
        \bcl\redis\base::get_rd()->set($this->path_give_, $give_uubean, 10);//10秒有效

    }

    //收礼
    public static function get($uid)
    {


        $path = "users:gift:get:".$uid;

        return cacheBase::get(
            $path,
            function () use ($uid) {

                $value = self::DB()->getOne("select sum(money) from log_gift where live_id=?", array($uid));

                if ($value == false) {
                    return 0;
                }

                return $value;


            },
            \configCache::user['info']
        );


    }

    //送礼
    public static function give($uid)
    {


        $path = "users:gift:give:".$uid;

        return cacheBase::get(
            $path,
            function () use ($uid) {

                $value = self::DB()->getOne("select sum(money) from log_gift where uid=?", array($uid));

                if ($value == false) {
                    return 0;
                }

                return $value;


            },
            \configCache::user['info']
        );


    }

    //会话送礼
    public static function give_session($session_id)
    {


        $value = self::DB()->getOne("select sum(money) from log_gift where session_id=?", array($session_id));

        if ($value == false) {
            return 0;
        }

        return (int)$value;

    }


    public static function recipient_list($uid)
    {

        $path = "users:gift:recipient:".$uid;

        return cacheBase::get(
            $path,
            function () use ($uid) {

                $info = cashNotesModel::DB()->getAll(
                    "select count(*) num,gift_id,g.img,g.`name`,g.`value` from log_gift as l left JOIN  gift  g on l.gift_id = g.id where gift_id <> 0 and live_id=? group by gift_id order by g.sort ",
                    array($uid)
                );


                $list = giftModel::get_all(1);


                foreach ($list as $k => $v) {

                    $list[$k]->num = 0;

                    foreach ($info as $x => $y) {
                        if ($list[$k]->id == $info[$x]->gift_id) {
                            $list[$k]->num = $info[$x]->num;


                        }
                    }
                }

                return $list;

            },
            \configCache::user['short']
        );


    }

    public static function give_list($uid)
    {

        $path = "users:gift:give_list:".$uid;

        return cacheBase::get(
            $path,
            function () use ($uid) {
                $info = cashNotesModel::DB()->getAll(
                    "select count(*) num,gift_id,g.img,g.`name`,g.`value` from log_gift as l left JOIN  gift  g on l.gift_id = g.id  where gift_id <> 0 and  uid=? group by gift_id  order by g.sort ",
                    array($uid)
                );

                return $info;

            },
            \configCache::user['short']
        );

    }


    public static function get_number($type)
    {


        $path = "share:number:".$type;

        return cacheBase::get(
            $path,
            function () use ($type) {

                if ($type == 2)  // the view
                {

                    //select  the config show number
                    $show_number = settingModel::DB()->getOne("SELECT input_value FROM ql_setting WHERE id=3");
                    $show_number = intval($show_number);

                    $sql = "SELECT sum(value) money,uid AS id FROM money";
                    $sql .= " LEFT JOIN users ON money.uid = users.id  WHERE auth_state <> 2 AND type=20 OR type=30";
                    $sql .= " GROUP BY uid ORDER BY sum(value) ASC LIMIT 0,".$show_number;

                    $info = cashNotesModel::DB()->getAll($sql);
                } else {
                    if ($type == 1)  // the host
                    {

                        //select  the config show number
                        $show_number = settingModel::DB()->getOne("SELECT input_value FROM ql_setting WHERE id=2");
                        $show_number = intval($show_number);

                        $info = cashNotesModel::DB()->getAll(
                            "select sum(value) money,uid as id from money  left join users on money.uid = users.id where auth_state = 2 group by uid order by sum(value) desc LIMIT 0,".$show_number
                        );
                    }
                }

                $list = usersDomain::format_simple_user_list($info, "id", 1);

                return $list;

            },
            \configCache::user['short']
        );

    }


    /**
     * get the rank list
     * @param $type
     * @return array
     */
    public static function get_number_new($type)
    {

        if ($type == 2)  // the view
        {

            //select  the config show number
            $config = settingModel::DB()->getAll("SELECT input_value,value,order_type FROM ql_setting WHERE id=3");

            $show_number = intval($config[0]->input_value);
            $show_is = intval($config[0]->value);

            if($config[0]->order_type == 'rand'){

                $sql = "SELECT * FROM rich_ranking  ORDER BY rand() lIMIT ".$show_number;
            }else{

                $sql = "SELECT * FROM rich_ranking lIMIT ".$show_number;
            }

            $info = cashNotesModel::DB()->getAll($sql);

            if ($show_is == 2) {

                foreach ($info as $k => $v) {
                    unset($info[$k]->total_consumption);
                    $info[$k]->total_money = '';
                }
            }


            //format the array
            foreach ($info as $k => $v) {
                $list[$k] = $v;
                $list[$k]->id = $v->uid;
            }

        } else {
            if ($type == 1) { // the host

                //select  the config show number
                $config = settingModel::DB()->getAll("SELECT input_value,value,order_type FROM ql_setting WHERE id=2");

                $show_number = intval($config[0]->input_value);
                $show_is = intval($config[0]->value);

                if($config[0]->order_type == 'rand'){

                    $sql = "SELECT * FROM charm_ranking  ORDER BY rand() lIMIT ".$show_number;
                }else{

                    $sql = "SELECT * FROM charm_ranking lIMIT ".$show_number;
                }

                $info = cashNotesModel::DB()->getAll($sql);

                if ($show_is == 2) {

                    foreach ($info as $k => $v) {
                        unset($info[$k]->total_income);
                        $info[$k]->total_money = '';

                    }
                }

                //format the array
                foreach ($info as $k => $v) {
                    $list[$k] = $v;
                    $list[$k]->id = $v->uid;
                }


            } else { // type =3 the new   get the rank list order by create time

                $show_number_sql = "SELECT input_value FROM ql_setting WHERE id=27";
                $show_number = settingModel::DB()->getOne($show_number_sql);

                $sql = "SELECT id FROM users WHERE auth_state=0 ORDER BY created_at desc lIMIT  ".$show_number;

                $id_list = cashNotesModel::DB()->getAll($sql);

                $info = usersDomain::format_simple_user_list($id_list, "id", 1);

                foreach ($info as $k=>$v){
                    $info[$k]->online_state = 40;
                }



            }
        }


        return $info;

    }


}
