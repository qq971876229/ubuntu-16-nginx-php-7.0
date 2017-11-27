<?php

namespace App\Models;

use App\RModels\logRModel;
use bcl\redis\cacheBase;
use App\Domain\usersDomain;
use App\Lib\System\App;


class moneyModel extends baseModel
{

    private $uid_;
    private $path_;


    public function set_uid($uid)
    {
        $path = "users:money:".$uid.":can";
        $this->path_ = $path;
        $this->uid_ = $uid;
    }


    public function getSource()
    {
        return "money";
    }


    public function getlist($page)
    {
        $uid = $this->uid_;

        return moneyModel::page($page, "select * from money where uid=? order by id desc", array($uid));

    }

    /*
    "recharge"=>"1",//充值
    "debug" => '10',//充值
    "view_live" => '20',//观众扣费
    "live_live" => '21',//主播收费
    "gift"=>"30",//送物
    "gift"=>"31",//收物
    "reference"=>"40",//推荐
    "mess"=>"50"//消息*/

    public function get_month_list($page)
    {

        $time = strtotime(date('Y-m-01', time()));

        $sql = "select * from money where uid=?  and add_time > $time 
            and ( type = 21 or type = 31 or type = 40) 
            order by id desc";


        $uid = $this->uid_;

        return moneyModel::page(
            $page,
            $sql,
            array($uid)
        );

    }

    public function get_week_list($page)
    {

        $beginThisweek = mktime(0, 0, 0, date('m'), date('d') - date('w') + 1, date('y'));


        $sql = "select * from money where uid=?  and add_time > $beginThisweek
        and ( type = 21 or type = 31 or type = 40)
        order by id desc";

        $uid = $this->uid_;

        return moneyModel::page(
            $page,
            $sql,
            array($uid)
        );

    }

    public static function clear_cache($uid)
    {
        \bcl\redis\base::get_rd()->del("users:money:".$uid.":can");
        \bcl\redis\base::get_rd()->del("users:money:".$uid.":cash");
    }

    public static function get_cash_money2($uid){

        $value = self::DB()->getOne("select sum(money) from cash_notes where is_paid = 0 and uid=?", array($uid));

        if ($value == false) {
            return 0;
        }

        return $value;
    }

    private static function get_cash_money($uid)
    {
        $value = self::DB()->getOne("select sum(money) from cash_notes where is_paid = 0 and uid=?", array($uid));

        if ($value == false) {
            return 0;
        }

        return $value;

    }



    private static function get_count_money($uid)
    {

        $value = self::DB()->getOne("select sum(value) from money where uid=?", array($uid));


        if ($value == false) {
            return 0;
        }


        return $value;
    }


    public function cash($value, $tied)
    {

        $cash_money = $this->get_cash_money($this->uid_);


        //App::Input()->error($this->uid_);

        if ($value > $cash_money) {
            App::Input()->error("提现金额大于提现余额");
        }


        $uid = $this->uid_;
        $uumoney = new moneyModel();
        $uumoney->type = \configPay::money_type['cash'];
        $uumoney->remark = "提现";
        $uumoney->value = -$value;
        $uumoney->uid = $uid;
        $uumoney->tied = $tied;
        $uumoney->add_time = time();
        $uumoney->save();

    }


    public function insert($value, $remark, $tied, $type,$session_id=0)
    {

        $uid = $this->uid_;
        $cur_value = self::get_value($uid);

        if (($cur_value + $value) < 0) {
            self::error("余额不足");
        }


        //logRModel::log("webhooks", "insert1");

        $this->update_cache($value);


        // logRModel::log("webhooks", "insert2");

        $uumoney = new moneyModel();
        $uumoney->type = $type;
        $uumoney->remark = $remark;
        $uumoney->value = $value;
        $uumoney->uid = $uid;
        $uumoney->tied = $tied;
        $uumoney->add_time = time();
        $uumoney->session_id = $session_id;
        $uumoney->save();

        //logRModel::log("webhooks", "insert3");

        // \bcl\redis\base::get_rd()->del($this->path_);//10分钟有效

        return $uumoney;
    }


    public function update_money($uid,$session_id,$value){

        $uumoney = moneyModel::findFirst("uid=$uid AND session_id=$session_id");

        $uumoney->value = $value;
        $uumoney->save();

        return $uumoney;

    }

    public function delete_session_id($session_id){

        $money = moneyModel::find("session_id=$session_id");
        foreach ($money as $k=>$v){
            $v->delete();
        }

    }

    /**
     * merge the same session record
     * @param $session_id
     */
    public static function merge_session($session_id){

        $live = vodSessionModel::findFirst($session_id);

        $transaction = new Transaction();

        $transaction->run(function()use($transaction,$live)
        {

            $logGiftModel = new logGiftModel();
            $logGiftModel->set_uid($live->view_id, $live->live_id);

            $transaction->set_commit($logGiftModel);

            $gift_record = $logGiftModel->add($live->money, "视频通话", time(),$live->id,0);

            $uumoney = new moneyModel();
            $uumoney->set_uid($live->view_id);

            $value = $live->money;

            $uumoney->insert(-$value, "与主播".$live->live_id."视频扣费", $live->id, \configPay::money_type['view_live'],$live->id);

            $uumoney = new moneyModel();
            $uumoney->set_uid($live->live_id);

            $value = round($live->money*\config::pay['rate'],2);

            $uumoney->insert($value, "与用户".$live->view_id."视频收入", $live->id, \configPay::money_type['live_live'],$live->id);

            $transaction->set_commit($uumoney);









            $uid = $live->view_id;
            $live_id = $live->live_id;
            $remark = "视频通信，用户".$uid."主播".$live_id;
            $total_money = $live->money;

            //观众推荐
            $view_reference = new usersDomain($uid);
            $live_reference = new usersDomain($live_id);


            $view_reference_id =  $view_reference->get_reference();
            if($view_reference_id == $uid){
                $view_reference_id = false;
            }

            $live_reference_id =  $live_reference->get_reference();


            if($view_reference_id != false)
            {

                $remark = "视频通信，推荐用户".$uid."主播".$live_id;

                $view_reference_money = new moneyModel();
                $view_reference_money->set_uid($view_reference_id);

                $transaction->set_commit($view_reference_money);

                //the talk reference money,if the view use money
                $view_reference_money->insert($total_money*\config::pay['reference_view'],
                    $remark."推荐奖励",$uid,\configPay::money_type['reference']);

            }


            if($live_reference_id !=false)
            {

                $remark = "视频通信，用户".$uid."推荐主播".$live_id;

                $live_reference_money = new moneyModel();
                $live_reference_money->set_uid($live_reference_id);

                $live_reference_money->insert($total_money*\config::pay['reference'],
                    $remark."推荐奖励",$live_id,\configPay::money_type['reference']);


                $transaction->set_commit($live_reference_money);

            }


            return true;

        }, "pay:".$live->view_id);


    }


    public static function get_value($uid)
    {

        $path = "users:money:".$uid.":can";

        return cacheBase::get(
            $path,
            function () use ($uid) {
                $value = self::get_count_money($uid);

                $cash_money = self::get_cash_money($uid);

                return $value - $cash_money;


            },
            \configCache::user['info']
        );

    }

    public static function get_cash_value($uid)
    {

        $path = "users:money:".$uid.":cash";

        return cacheBase::get(
            $path,
            function () use ($uid) {

                return self::get_cash_money($uid);


            },
            \configCache::user['info']
        );

    }

    public function commit()
    {
        \bcl\redis\base::get_rd()->expire($this->path_, 60 * 10);
    }

    public function rollback()
    {

        if ($this->original_value_ == null) {
            return;
        }


        self::clear_cache($this->uid_);
    }


    private $original_value_ = null;

    private function update_cache($change_value)
    {

        $uid = $this->uid_;
        $value = self::get_value($uid);

        if ($this->original_value_ == null) {
            $this->original_value_ = $value;
        }

        $value = $value + $change_value;

        // echo $value."$".$this->path_;


        \bcl\redis\base::get_rd()->set($this->path_, $value, 10);//10秒有效

    }


    public static function get_user_money($uid)
    {


        $time = strtotime(date('Y-m-01', time()));


        $sql = "select sum(value) from money where uid=?  and add_time > ?
                and ( type = 21 or type = 31 or type = 40)";


        $month_value = self::DB()->getOne($sql, array($uid, $time));

        if ($month_value == false) {
            $month_value = 0;
        }


        $beginThisweek = mktime(0, 0, 0, date('m'), date('d') - date('w') + 1, date('y'));


        $sql = "select sum(value) from money where uid=?  and add_time > ?
                and ( type = 21 or type = 31 or type = 40)";


        $week_value = self::DB()->getOne($sql, array($uid, $beginThisweek));

        if ($week_value == false) {
            $week_value = 0;
        }

        $data = new \stdClass();
        $data->week_money = $week_value;
        $data->month_money = $month_value;


        return $data;


    }

    public static function get_share_pc($page, $uid)
    {

        if ($uid == 0) {
            $sql = "select * from money where type=40";
        } else {
            $sql = "select * from money where uid=$uid and type=40";
        }


        $list = self::page_pc($page, $sql);

        $list['list'] = usersDomain::format_simple_user_list($list['list'], "uid");

        foreach ($list['list'] as $k => $v) {
            $list['list'][$k]->img = \config::app['img_bucket_url'].$list['list'][$k]->img;

            $list['list'][$k]->add_time = date('Y-m-d H:i:s', $list['list'][$k]->add_time);
        }


        return $list;
    }

    /**
     * get the total recharge by the uid
     * @param $uid
     * @return int
     */
    public static function get_total_recharge($uid)
    {

        $sql = "SELECT sum(value) FROM money WHERE uid = $uid AND (type=1 OR type=10)";
        $total_recharge = moneyModel::DB()->getOne($sql);
        if (!$total_recharge) {
            $total_recharge = 0;
        }

        return $total_recharge;
    }

    /**
     * get the balance in the account
     * @param $uid
     * @return \stdClass|void
     */
    public static function get_balance($uid)
    {
        $user = new usersDomain($uid);

        $balance = $user->get_cash_account();

        return round($balance->money,2);

    }

    /**
     * get the recommend money
     * @param $uid
     * @return int
     */
    public static function get_recommend_money($uid)
    {

        $sql = "SELECT sum(value) FROM money WHERE uid = $uid AND type=40";
        $recommend_money = moneyModel::DB()->getOne($sql);
        if (!$recommend_money) {
            $recommend_money = 0;
        }

        return $recommend_money;

    }

    /**
     * get the talk money
     * @param $uid
     * @return int
     */
    public static function get_talk_money($uid, $host = 1)
    {
        if ($host == 1) {
            $type = 21;
        } else {
            $type = 20;
        }

        $sql = "SELECT sum(value) FROM money WHERE uid = $uid AND type=$type";

        $talk_money = moneyModel::DB()->getOne($sql);
        if (!$talk_money) {
            $talk_money = 0;
        }

        return $talk_money;

    }

    /**
     * get the first recharge number of today
     * @return false|int
     */
    public static function get_today_first_recharge_num()
    {

        $today_time = strtotime(date("Y-m-d", time()));

        $sql = "SELECT distinct(uid) FROM money WHERE add_time>$today_time AND type=1";

        $today_recharge = moneyModel::DB()->getAll($sql);

        foreach ($today_recharge as $k => $v) {

            $sql = "SELECT uid FROM money WHERE add_time<=$today_time AND type=1 AND uid=".$v->uid;
            $result = moneyModel::DB()->getOne($sql);
            if ($result) {
                unset($today_recharge[$k]);
            }

        }


        return count($today_recharge);
    }

    /**
     * @param $date
     * @return mixed
     */
    public static function get_total_recharge_by_date($date)
    {

        $start_time = strtotime($date);
        $end_time = strtotime($date.'23:59');
        $sql = "SELECT SUM(value) FROM money WHERE type=1 AND add_time>=".$start_time." AND add_time<".$end_time;

        $today_recharge = moneyModel::DB()->getOne($sql);

        return $today_recharge;

    }

    public static function get_reference_recharge_by_date($date)
    {

        $start_time = strtotime($date);
        $end_time = strtotime($date.'23:59');
        $sql = "SELECT SUM(value) FROM money LEFT JOIN users ON money.uid=users.id WHERE money.type=1 AND users.reference<>0 AND money.add_time>=".$start_time." AND money.add_time<".$end_time;

        $reference_recharge = moneyModel::DB()->getOne($sql);

        return $reference_recharge;
    }


    /**
     * @param $date
     * @return int
     */
    public static function get_first_recharge_people_num_by_date($date)
    {

        $start_time = strtotime($date);
        $end_time = strtotime($date.'23:59');
        $sql = "SELECT distinct(uid) FROM money WHERE type=1 AND add_time>=".$start_time." AND add_time<".$end_time;

        $today_recharge = moneyModel::DB()->getAll($sql);

        foreach ($today_recharge as $k => $v) {

            $sql = "SELECT uid FROM money WHERE add_time<$start_time AND type=1 AND uid=".$v->uid;

            $result = moneyModel::DB()->getOne($sql);

            if ($result) {
                unset($today_recharge[$k]);
            }

        }

        return count($today_recharge);
    }

    /**
     * @param $date
     * @return int
     */
    public static function get_ios_recharge_by_date($date)
    {

        $start_time = strtotime($date);
        $end_time = strtotime($date.'23:59');
        $sql = "SELECT SUM(value) FROM money LEFT JOIN users ON money.uid=users.id WHERE money.type=1 AND users.phone_type='ios' AND money.add_time>=".$start_time." AND money.add_time<".$end_time;

        $ios_recharge = moneyModel::DB()->getOne($sql);

        return $ios_recharge;
    }

    public static function get_android_recharge_by_date($date)
    {

        $start_time = strtotime($date);
        $end_time = strtotime($date.'23:59');
        $sql = "SELECT SUM(value) FROM money LEFT JOIN users ON money.uid=users.id WHERE money.type=1 AND users.phone_type='android' AND money.add_time>=".$start_time." AND money.add_time<".$end_time;

        $ios_recharge = moneyModel::DB()->getOne($sql);

        return $ios_recharge;
    }

    /**
     * @param $date
     * @return int
     */
    public static function get_total_order_num_by_date($date)
    {

        $start_time = strtotime($date);
        $end_time = strtotime($date.'23:59');
        $sql = "SELECT count(id) FROM money  WHERE type=1 AND add_time>=".$start_time." AND add_time<".$end_time;

        $total_order = moneyModel::DB()->getOne($sql);

        return $total_order;
    }

    public static function get_order_num_by_date_and_type($date, $type = 10)
    {

        $start_time = strtotime($date);
        $end_time = strtotime($date.'23:59');
        $sql = "SELECT count(id) FROM money  WHERE type=1 AND value=$type AND add_time>=".$start_time." AND add_time<".$end_time;

        $total_order = moneyModel::DB()->getOne($sql);

        return $total_order;

    }


    /**
     * get the gift money
     * @param $uid
     * @param int $host
     * @return int
     */
    public static function get_gift_money($uid, $host = 1)
    {
        if ($host == 1) {
            $type = 31;
        } else {
            $type = 30;
        }

        $sql = "SELECT sum(value) FROM money WHERE uid = $uid AND type=$type";

        $talk_money = moneyModel::DB()->getOne($sql);
        if (!$talk_money) {
            $talk_money = 0;
        }

        return $talk_money;

    }


}
