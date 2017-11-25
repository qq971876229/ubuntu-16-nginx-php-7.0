<?php

namespace App\Models;

use Phalcon\Mvc\Model;
use Phalcon\Mvc\Model\Resultset\Simple as Resultset;
use App\RModels\usersRModel;
use App\RModels\users_simple_RModel;
use Phalcon\Mvc\Model\Behavior\Timestampable;
use bcl\redis\cacheBase;
use App\Lib\System\App;
use App\Domain\usersDomain;
use App\Domain\payDomain;


class vodSessionModel extends baseModel
{


    private static $ttl_ = 30;


    public function getSource()
    {
        return "vod_session";
    }

    public static function get($id)
    {

        $model = vodSessionModel::findFirst("id='$id'");

        return $model;
    }

    //接听率
    public static function get_answer_rate($uid)
    {
        $path = "users:answer_rate:".$uid;

        return cacheBase::get(
            $path,
            function () use ($uid) {

                $value2 = self::DB()->getOne(
                    "SELECT count(*) from vod_session where state=2 and live_id=?",
                    array($uid)
                );


                $value3 = self::DB()->getOne(
                    "SELECT count(*) from vod_session where state=3 and live_id=?",
                    array($uid)
                );


                if (($value2 + $value3) == 0) {
                    $value = 0;
                } else {
                    $value = round(($value2 / ($value2 + $value3)) * 100);
                }


                return $value."%";


            },
            \configCache::user['info']
        );

    }

    public static function is_live($uid)
    {

        $model = vodSessionModel::findFirst("live_id=$uid and state =1");

        if ($model == false) {
            return false;
        }

        return true;


    }

    /**
     * if the user over the video ,but the vod session don't over
     * this method over the session state
     * @param $uid
     */
    public static function free_session($uid)
    {
        $user = usersModel::info($uid);

        if($user->auth_state==2){  //the user is host

            $sql = "SELECT id FROM vod_session WHERE live_id=$uid AND state=1 ";

        }else{  // the user is view

            $sql = "SELECT id FROM vod_session WHERE view_id=$uid AND state=1 ";

        }

        $session_ids = vodSessionModel::DB()->getAll($sql);

        foreach ($session_ids as $k => $v) {

            $session = vodSessionModel::findFirst($v->id);

            $session->state = 2;

            $session->save();

        }


    }


    /**
     * get the time how long talk
     * @param $uid
     * @param int $host
     * @return float|int
     */
    public static function get_talk_long($uid, $host = 1)
    {


        $sql = "SELECT * FROM vod_session";

        if ($host == 1) {
            $sql .= " WHERE live_id=$uid";
        } else {
            $sql .= " WHERE view_id=$uid";
        }

        $vod_sessions = vodSessionModel::DB()->getAll($sql);

        $talk_long = 0;
        foreach ($vod_sessions as $k => $v) {

            if ($v->begin_time && $v->end_time && $v->state == 2) {
                $talk_long += ($v->end_time - $v->begin_time) / 60;
            } else {
                continue;
            }
        }

        $talk_long = sprintf("%.2f", $talk_long);

        return $talk_long;


    }

    /**
     * get the talk times
     * @param $uid
     * @param int $host
     * @return mixed
     */
    public static function get_talk_times($uid, $host = 1)
    {
        $sql = "SELECT count(*) FROM vod_session";

        if ($host == 1) {
            $sql .= " WHERE live_id=$uid";
        } else {
            $sql .= " WHERE view_id=$uid";
        }

        $talk_times = vodSessionModel::DB()->getOne($sql);

        return $talk_times;

    }

    /**
     * get the success talk times
     * @param $uid
     * @param int $host
     * @return mixed
     */
    public static function get_talk_times_success($uid, $host = 1)
    {
        $sql = "SELECT count(*) FROM vod_session";

        if ($host == 1) {
            $sql .= " WHERE live_id=$uid";
        } else {
            $sql .= " WHERE view_id=$uid";
        }

        $sql .= " AND (state=1 or state=2)";

        $talk_times = vodSessionModel::DB()->getOne($sql);

        return $talk_times;

    }

    /**
     * get the fail talk times
     * @param $uid
     * @param int $host
     * @return mixed
     */
    public static function get_talk_times_fail($uid, $host = 1)
    {
        $sql = "SELECT count(*) FROM vod_session";

        if ($host == 1) {
            $sql .= " WHERE live_id=$uid";
        } else {
            $sql .= " WHERE view_id=$uid";
        }

        $sql .= " AND (state<>1 AND state<>2) ";

        $talk_times = vodSessionModel::DB()->getOne($sql);

        return $talk_times;

    }

    /**
     * get the user's talk status
     * @param $uid
     * @param int $host
     * @return string
     */
    public static function get_talk_status($uid, $host = 1)
    {
        $sql = "SELECT id,state FROM vod_session ";


        if ($host == 1) {
            $sql .= " WHERE live_id=$uid";
        } else {
            $sql .= " WHERE view_id=$uid";
        }

        $list = vodSessionModel::DB()->getAll($sql);
        foreach ($list as $k => $v) {
            if($v->state==1){
                $return = '通话中';
                break;
            }else{
                $return = '正常';
            }
        }

        return $return;



    }


    //关闭会话
    public static function close_session($model)
    {

        //加锁
        $lock = new \bcl\redis\lockBase("vod_session:".$model->id);

        if ($lock->lock() == false) {
            App::Input()->error("点击太快");
        }


        payDomain::pay_live_minute($model);
        payDomain::pay_reference($model);

        $view_user = new usersDomain($model->view_id);

        $value = self::get_live_money($model);  // give the live how much
//        $user_money = $view_user->full_info()->money; // the user's balance
//
//        if ($value >= $user_money) {
//            $value = $user_money; // don't let user's balance minus
//        }

        $model->end_time = time();
        $model->money = $value;
        $model->state = 2;
        $model->save();

//        $live = usersModel::findFirst($model->live_id);
//        $live->online_state = 40; // end the session,free the live's online state
//        $live->save();



//        moneyModel::merge_session($model->id);
        $lock->unlock();


    }

    //关闭申请
    public static function close_accept($model)
    {

        $model->state = 3;
        $model->save();

    }


    public static function close_live($view_id, $live_id)
    {

        return;
        $time = time() + 60;

        $sign = MD5("ca61bfd219f407d19735b29b2a39819e".$time);


        $url = "http://fcgi.video.qcloud.com/common_access?appid=1252500699&interface=Live_Channel_SetStatus&t=$time&sign=$sign";

        $view_url = $url."&Param.s.actio=forbid&Param.s.channel_id=4555_$view_id&Param.n.abstime_end=1000";

        $live_url = $url."&Param.s.actio=forbid&Param.s.channel_id=4555_$live_id&Param.n.abstime_end=1000";


        //初始化
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $view_url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        $output = curl_exec($ch);


        curl_close($ch);


        //初始化
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $live_url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        $output = curl_exec($ch);


        curl_close($ch);


    }

    public static function end_session($model)
    {
        if ($model->state == 0) {
            self::close_accept($model);
        } else {
            if ($model->state == 1) {
                self::close_session($model);
            }
        }

    }


    /**
     * the vod money
     * @param $live  the vod session model
     * @return float|int
     */
    public static function get_live_money($live)
    {

        if ($live->state == 3 || $live->state == 0) {

            return 0;
        }

        if ($live->state == 2) {  // session end 

            if($live->end_time === 0){

                $time = time() - $live->begin_time;
            }else{

                $time = $live->end_time - $live->begin_time;
            }
        } else {

            $time = time() - $live->begin_time;
        }

        $value = round(ceil($time / 60) * $live->price, 2);

        if ($value < $live->price) {
            $value = $live->price;
        }

        return $value;
    }


    public static function count_live($live,$from='live')
    {

        $value = self::get_live_money($live);

        if ($value < $live->price) {
            $value = $live->price;
        }

        //Determine if the remaining money is greater than the price,if litter,the close time should greater than 49  second
        $current_second = (time()-$live->begin_time)%60;

        $view_user = new usersDomain($live->view_id);

        usersModel::clear($live->view_id);
        usersModel::clear($live->live_id);

        $user_balance_sql = "SELECT SUM(value) FROM money WHERE uid=$live->view_id";
        $user_balance = moneyModel::DB()->getOne($user_balance_sql);

        if(!$user_balance){
            $user_balance = round($view_user->full_info()->money - $value+$live->price, 2);  // the user's balance ,the full into from cache
        }

//        if(!$user_balance){
//            $user_balance = round($view_user->full_info()->money - $value+$live->price, 2);  // the user's balance ,the full into from cache
//        }

        if ($live->price > $user_balance && $current_second>49  ) {

            self::end_session($live);
            return true;
        }


        // if user's request time apart from now greater than 30 seconds
//        if(time()-$live->end_time>120 && $current_second>39){
//
//            self::end_session($live);
//            return true;
//        }

        // pay the live every minute
        if ($current_second >= 0 && $current_second < 15 && $from == 'view') {

            if ($user_balance >= $live->price){


                payDomain::pay_live_minute($live);
            }
            else{

                self::end_session($live);
            }
        }



    }


    /**
     * @param $live
     * @param string $from
     * @return bool
     */
    public static function count_live_redis($live,$from='live')
    {

        //Determine if the remaining money is greater than the price,if litter,the close time should greater than 49  second
        $current_second = (time()-$live->begin_time)%60;

        $user_balance = usersModel::get_balance($live->view_id);

        if ($live->price > $user_balance && $current_second>49  ) {

            self::end_session($live);
            return true;
        }

        // if user's request time apart from live's request time greater than 30 seconds  ===== start

        if($live->live_ttl- $live->view_ttl>30 && $current_second > 39 ){

            self::end_session($live);
            return true;
        }

        // if user's request time apart from live's request time greater than 30 seconds  ===== end


        // pay the live every minute
        if ($current_second >= 0 && $current_second < 15 && $from == 'view') {
//        if ($from == 'view') {

            if ($user_balance >= $live->price){

                // update the database
                payDomain::pay_live_minute($live);



            }

        }


    }


    public static function check_session()
    {


        $lock_key = "vod:checksession";
        $lock = new \bcl\redis\lockBase($lock_key);

        if ($lock->lock() == false) {
            App::Input()->error("点击太快");
        }


        $list = vodSessionModel::find("state = 0 or state = 1");


        $time = time();


        foreach ($list as $v) {

            if ($v->view_ttl > $time && $v->live_ttl > $time) {

                if ($v->state == 0) {
                    continue;
                }

                self::count_live($v);
            } else {


                self::end_session($v);
            }


        }


        $lock->unlock();


    }

    public static function session_list($id, $page)
    {
        $list = cashNotesModel::page(
            $page,
            "select * from vod_session where (view_id=?  or live_id=?) and (state = 3 or state = 2) order by id desc",
            array($id, $id)
        );


        $res_list = array();


        foreach ($list as $v) {
            $row = new \stdClass();

            $row->id = $v->id;


            if ($v->view_id == $id) {

                $row->uid = $v->live_id;


                $row->other_uid = $v->view_id;

                $row->type = "call";
            } else {
                if ($v->live_id == $id) {
                    $row->uid = $v->view_id;

                    $row->other_uid = $v->live_id;

                    $row->type = "answer";
                }
            }

            $row->create_time = $v->create_time;


            if ($v->state == 2) {
                $row->session_state = "success";
            } else {
                if ($v->state == 3) {
                    $row->session_state = "fail";
                }
            }

            $row->count_money = $v->money;

            $res_list[] = $row;

        }

        $res_list = usersDomain::format_simple_user_list($res_list,'uid',1);

        foreach ($res_list as $k => $v) {
            $time = vodSessionModel::DB()->getAll("SELECT begin_time,end_time FROM vod_session WHERE id=".$v->id);
            $res_list[$k]->begin_time = $time[0]->begin_time;
            $res_list[$k]->end_time = $time[0]->end_time;
        }


        return $res_list;
    }

    //结束会话
    public static function end($id)
    {
        $model = vodSessionModel::findFirst("id='$id'");

        if ($model == false) {
            App::Input()->error("会话不存在");
        }

        usersModel::sync_balance($model->view_id);

        self::end_session($model);

        $data = new \stdClass();

        $data->money = $model->money;

        $data->begin_time = $model->begin_time;
        if($model->end_time===0){

            $data->end_time = $model->begin_time;
        }else{

            $data->end_time = $model->end_time;
        }
        $data->gift = $model->gift_money;

        return $data;

    }

    public static function accept($id, $live_id)
    {
        $sql = "id='$id' and live_id='$live_id'  and state =0";


        $model = vodSessionModel::findFirst($sql);

        if ($model == false) {
            App::Input()->error("会话不存在");
        }

        $time = time();

        $model->state = 1;
        $model->begin_time = $time;
        $model->end_time = $time;
        $model->live_ttl = $time + vodSessionModel::$ttl_;
        $model->view_ttl = $time + vodSessionModel::$ttl_;

        $live = new usersDomain($live_id);

        $model->money = round(($live->full_info()->price * 0.5), 2);

        $model->save();

//        $live = usersModel::findFirst($model->live_id);
//        $live->online_state = 30; // end the session,busy the live's online state
//        $live->save();

        //App::TxMess()->live_accept($live_id,$model->view_id, array("id"=>$model->id));

        return $model;


    }

    public static function accept_redis($session_id, $live_id)
    {
        $sql = "id='$session_id' and live_id='$live_id'  and state =0";

        $model = vodSessionModel::findFirst($sql);

        if ($model == false) {
            App::Input()->error("会话不存在");
        }

        $time = time();

        $model->state = 1;
        $model->begin_time = $time;
        $model->end_time = $time;
        $model->live_ttl = $time + vodSessionModel::$ttl_;
        $model->view_ttl = $time + vodSessionModel::$ttl_;

        $model->save();

        return $model;


    }


    static function createTxTime($now_time)
    {
        //	$now_time = time();
        $now_time += 12 * 60 * 60;

        return dechex($now_time);
    }

    static function ceatePushURLTxSecret($streaid, $txTime)
    {
        $md5_val = md5(\config::app['live_push_url_key'].$streaid.$txTime);

        return $md5_val;
    }


    static function create_url($uid, $is_debug_time)
    {

        //--------
        $stream_url = \config::app['app_name'].$uid;

        //$stream_url = \config::app['live_url'].$uid;

        $bizid = \config::app['live_bizid'];

        $list = explode('/', $stream_url);
        $length = count($list);
        $live_code = $bizid."_".$stream_url;

        $now_time = time();
        $txTime = vodSessionModel::createTxTime($now_time);

        if (strlen($is_debug_time) > 3) {
            $txTime = $is_debug_time;
        }


        $safe_url = $stream_url."?bizid=".$bizid."&txSecret=".vodSessionModel::ceatePushURLTxSecret(
                $live_code,
                $txTime
            )."&txTime=".$txTime;

        return $safe_url;
    }


    public static function start($view_id, $live_id, $is_debug, $is_debug_time)
    {

        vodSessionModel::free_session($view_id);

        $model = new vodSessionModel();

        $start_model = usersModel::DB()->getOne(
            "select * from vod_session where state = 1  and (live_id = ? or view_id = ?)",
            array($view_id, $view_id)
        );


        if ($start_model == true) {
            App::Input()->error("观众已经在视频中");
        }


        $live_model = usersModel::DB()->getOne(
            "select * from vod_session where state = 1 and (live_id = ? or view_id = ?)",
            array($live_id, $live_id)
        );

        if ($live_model != false) {
            App::Input()->error("主播已经在视频中");
        }


        if ($is_debug == 0) {
            $online_state = App::TxMess()->check_online($live_id);
        } else {
            $online_state = 1;
        }



        $live_user = new usersDomain($live_id);

        if ($live_user->simple_info() == false) {
            App::Input()->error("主播不存在");
        }

        if ($live_user->simple_info()->is_live == 0) {
            App::Input()->error("不能连麦观众");
        }


        if ($live_user->simple_info()->online_state == \configLive::user_online_state['disturb']) {
            App::Input()->error("主播勿扰");
        }


        $view_user = new usersDomain($view_id);

        if ($live_user->simple_info() == false) {
            App::Input()->error("观众不存在");
        }


        $user_data = $view_user->full_info();


        if ($user_data->money < $live_user->simple_info()->price) {
            App::Input()->error("余额必须大于".$live_user->simple_info()->price."元");
        }

        $model->view_id = $view_id;
        $model->live_id = $live_id;
        $model->nickname = $user_data->nickname;
        $model->img = $user_data->img;
        $model->view_ttl = time() + 60 * 10;//10分钟失效
        $model->live_ttl = $model->view_ttl;//10分钟失效
        $model->create_time = time();
        $model->state = 0;

        if ($online_state == 1) {
            $model->is_offline = 0;
        } else {
            $model->is_offline = 1;

        }


        $model->price = $live_user->simple_info()->price;


        $view_url = vodSessionModel::create_url($view_id, $is_debug_time);
        $live_url = vodSessionModel::create_url($live_id, $is_debug_time);


        $model->view_url = $view_url;
        $model->live_url = $live_url;


        $model->save();


        if ($is_debug == 0) {
            // App::TxMess()->live_start($view_id, $live_id, array("id"=>$model->id));
        }


        return $model;

    }

    public static function start_redis($view_id, $live_id, $is_debug, $is_debug_time)
    {

        vodSessionModel::free_session($view_id);

        $vod_start_balance = usersModel::sync_balance($view_id);

        // the vod max give the live money  ===== start
        $vod_max_money = usersModel::update_vod_max_money($view_id,$vod_start_balance);
        // the vod max give the live money  ===== end

        $model = new vodSessionModel();

        $start_model = usersModel::DB()->getOne(
            "select * from vod_session where state = 1  and (live_id = ? or view_id = ?)",
            array($live_id, $view_id)
        );

        $live_model = $start_model;

        if ($live_model != false) {
            App::Input()->error("主播已经在视频中");
        }


        if ($start_model == true) {
            App::Input()->error("观众已经在视频中");
        }

        if ($is_debug == 0) {
            $online_state = App::TxMess()->check_online($live_id);
        } else {
            $online_state = 1;
        }

        $live_user = new usersDomain($live_id);

        $live_user_info = $live_user->simple_info();

        if ($live_user_info == false) {
            App::Input()->error("主播不存在");
        }

        if ($live_user_info->is_live == 0) {
            App::Input()->error("不能连麦观众");
        }


        if ($live_user_info->online_state == \configLive::user_online_state['disturb']) {
            App::Input()->error("主播勿扰");
        }

        $view_user = new usersDomain($view_id);

        $user_data = $view_user->full_info();

        if ($user_data == false) {
            App::Input()->error("观众不存在");
        }

        if ($user_data->money < $live_user_info->price) {
            App::Input()->error("余额必须大于".$live_user_info->price."元");
        }

        $model->view_id = $view_id;
        $model->live_id = $live_id;
        $model->nickname = $user_data->nickname;
        $model->img = $user_data->img;
        $model->view_ttl = time() + 60 * 10;//10分钟失效
        $model->live_ttl = $model->view_ttl;//10分钟失效
        $model->money = $live_user_info->price;
        $model->create_time = time();
        $model->state = 0;

        if ($online_state == 1) {

            $model->is_offline = 0;
        } else {

            $model->is_offline = 1;
        }

        $model->price = $live_user_info->price;

        $view_url = vodSessionModel::create_url($view_id, $is_debug_time);
        $live_url = vodSessionModel::create_url($live_id, $is_debug_time);

        $model->view_url = $view_url;
        $model->live_url = $live_url;

        $model->save();

        return $model;

    }


    public static function heartbeat_live($id, $uid)
    {

        $sql = "id='$id' and live_id='$uid'";

        $model = vodSessionModel::findFirst($sql);

        if ($model == false) {
            App::Input()->error("会话不存在");

        }

        vodSessionModel::count_live($model,'live');

        if ($model->state == 3) {
            App::Input()->error(["mess" => "会话已结束", "debug" => $model], \config::error['live_end']);
        }

        if ($model->state == 2) {
            App::Input()->error(["mess" => "会话已结束", "debug" => $model], \config::error['live_end']);
        }

        $model->live_ttl = time() + vodSessionModel::$ttl_;

        $model->save();

        $data = new \stdClass();

        $money = self::get_live_money($model);

        $data->time = time() - $model->begin_time;
        $data->money = $money;
        $data->gift = $model->gift_money;
        $data->debug = new \stdClass();

        $data->debug->data = ["cur_time" => time(), "live_ttl" => $model->live_ttl, "ttl" => $model->live_ttl - time()];
        $data->debug->info = $model;


        return $data;

    }

    public static function heartbeat_live_redis($id, $uid)
    {

        $sql = "id='$id' and live_id='$uid'";

        $model = vodSessionModel::findFirst($sql);

        if ($model == false) {
            App::Input()->error("会话不存在");
            self::end($id);
        }

        vodSessionModel::count_live_redis($model,'live');

        if ($model->state == 3) {
            App::Input()->error(["mess" => "会话已结束", "debug" => $model], \config::error['live_end']);
            self::end($id);
        }

        if ($model->state == 2) {
            App::Input()->error(["mess" => "会话已结束", "debug" => $model], \config::error['live_end']);
            self::end($id);
        }

        $model->live_ttl = time() + vodSessionModel::$ttl_;

        if($model->begin_time == 0){
            $model->begin_time = time();
        }

        $model->save();

        // return information
        $data = new \stdClass();

        $money = self::get_live_money($model);

        $data->time = time() - $model->begin_time;
        $data->money = $money;
        $data->gift = $model->gift_money;
        $data->debug = new \stdClass();

        $data->debug->data = ["cur_time" => time(), "live_ttl" => $model->live_ttl, "ttl" => $model->live_ttl - time()];
        $data->debug->info = $model;


        return $data;

    }

    public static function heartbeat_view($id, $uid)
    {
        $sql = "id='$id' and view_id='$uid'";

        $model = vodSessionModel::findFirst($sql);

        if ($model == false) {
            App::Input()->error("会话不存在");
            self::end($id);
        }

        if ($model->state == 3) {
            App::Input()->error(["mess" => "会话已结束", "debug" => $model], \config::error['live_end']);
            self::end($id);

        }

        if ($model->state == 2) {
            App::Input()->error(["mess" => "会话已结束", "debug" => $model], \config::error['live_end']);
            self::end($id);
        }

        // judge the balance enough,and pay the live every minute
        vodSessionModel::count_live($model,'view');

        // update the vod session record
        $model->view_ttl = time() + vodSessionModel::$ttl_;
        $model->end_time = time(); // update the last request time,the user's request time is real time

        $money = self::get_live_money($model);
        $model->money = $money;  // update the money

        $model->save();

        // return information
        $view_user = new usersDomain($uid);

        $data = new \stdClass();
        $data->money = $money;
        $data->time = time() - $model->begin_time;

        // user's balance
        $user_balance_sql = "SELECT SUM(value) FROM money WHERE uid=$uid";
        $user_balance = moneyModel::DB()->getOne($user_balance_sql);

        if(!$user_balance){
            $user_balance = round($view_user->full_info()->money - $money+$model->price, 2);  // the user's balance ,the full into from cache
        }

//        $data->user_money = round($view_user->full_info()->money - $data->money, 2);
        $data->user_money = $user_balance;

        $data->gift = $model->gift_money;

        $data->debug = new \stdClass();


        $data->debug->data = ["cur_time" => time(), "view_ttl" => $model->view_ttl, "ttl" => $model->view_ttl - time()];
        $data->debug->info = $model;


        return $data;

    }

    public static function heartbeat_view_redis($session_id, $uid)
    {
        $sql = "id='$session_id' and view_id='$uid'";

        $model = vodSessionModel::findFirst($sql);

        if ($model == false) {
            App::Input()->error("会话不存在");
            self::end($id);
        }

        if ($model->state == 3) {
            App::Input()->error(["mess" => "会话已结束", "debug" => $model], \config::error['live_end']);
            self::end($id);
        }

        if ($model->state == 2) {
            App::Input()->error(["mess" => "会话已结束", "debug" => $model], \config::error['live_end']);
            self::end($id);
        }

        // judge the balance enough,and pay the live every minute
        vodSessionModel::count_live_redis($model,'view');

        // update the vod session record
        $model->view_ttl = time() + vodSessionModel::$ttl_;

        if ($model->begin_time === 0) {
            $model->begin_time = time();
        }

        $model->end_time = time(); // update the last request time,the user's request time is real time

        $money = self::get_live_money($model);
        $model->money = $money;  // update the money

        $model->save();

        // return information
        $data = new \stdClass();
        $data->money = $money;
        $data->time = time() - $model->begin_time;

        // user's balance
        $user_balance = usersModel::get_balance($uid);

        $data->user_money = $user_balance;

        $data->gift = $model->gift_money;

        $data->debug = new \stdClass();

        $data->debug->data = ["cur_time" => time(), "view_ttl" => $model->view_ttl, "ttl" => $model->view_ttl - time()];
        $data->debug->info = $model;


        return $data;

    }


}
