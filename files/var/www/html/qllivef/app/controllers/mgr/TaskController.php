<?php

namespace App\Controllers\mgr;

use App\Domain\usersDomain;
use App\Lib\System\App;
use App\Models\charmRankingModel;
use App\Models\onlineTimeModel;
use App\Models\rechargeCalcModel;
use App\Models\richRankingModel;
use App\Models\settingModel;
use App\Models\systemMessModel;
use App\Models\usersModel;
use App\Models\moneyModel;
use App\Models\usersStateModel;
use App\Models\vodSessionModel;
use App\RModels\userTimeCountRModel;

class TaskController extends baseController
{


    /**
     * timed task
     * calc the answer rate by the vod session,set to the users table
     */
    public function calc_answer_rateAction()
    {

        $list = usersModel::find();

        foreach ($list->toArray() as $k => $v) {

            $answer_rate = vodSessionModel::get_answer_rate($v['id']);
            $user = usersModel::findFirst('id = '.$v['id']);

            $user->answer_rate = $answer_rate;

            $user->save();

        }

    }

    /**
     * timed task
     * get the online state from tencent ,and set state to users table
     */
    public function set_online_stateAction()
    {

        $list = usersModel::find();

        foreach ($list->toArray() as $k => $v) {

            $online_state = (int)usersStateModel::get($v['id']);

            $user = usersModel::findFirst('id = '.$v['id']);

            $user->online_state = $online_state;

            $user->save();

        }

    }

    /**
     * timed task
     * sync the charm ranking to the charm ranking table
     */
    public function sync_charm_rankingAction()
    {

        charmRankingModel::DB()->query_sql("delete from charm_ranking");

        //select config
        $config = settingModel::DB()->getAll("SELECT start_time,end_time FROM ql_setting WHERE id=2");

        $start_time = $config[0]->start_time;
        $end_time = $config[0]->end_time;

        $sql = "SELECT sum(value) money,money.uid AS id FROM money";
        $sql .= "  LEFT JOIN users ON money.uid = users.id ";
        $sql .= " WHERE users.auth_state = 2 AND (type=21 OR type=31) AND money.add_time>$start_time AND money.add_time<$end_time ";
        $sql .= " GROUP BY money.uid ORDER BY sum(value) DESC ";


        // youmei charm rank add recommend income
        $http_host = $_SERVER['HTTP_HOST'];

        if ($http_host == 'ymapi.miyintech.com') {

            $sql = "SELECT sum(value) money,money.uid AS id FROM money";
            $sql .= "  LEFT JOIN users ON money.uid = users.id ";
            $sql .= " WHERE users.auth_state = 2 AND (type=21 OR type=31 OR type=40) AND money.add_time>$start_time AND money.add_time<$end_time ";
            $sql .= " GROUP BY money.uid ORDER BY sum(value) DESC ";
        }

        $base_info = moneyModel::DB()->getAll($sql);

        foreach ($base_info as $k => $vo) {
            $base_info_new[$vo->id] = $vo->money;
        }

        $list = usersDomain::format_simple_user_list($base_info, 'id', 1);

        foreach ($list as $v) {

            $user = usersModel::findFirst("id=".$v->id);
            if (!$user) {
                continue;
            }

            $charm = new charmRankingModel();
            $charm->uid = $v->id;
            $charm->nickname = $v->nickname;
            $charm->img = $v->img;
            $charm->total_income = $base_info_new[$v->id];
            $charm->total_money = $base_info_new[$v->id];
            $charm->location = $v->location;
            $charm->birthday = $v->birthday;
            $charm->price = $v->price;
            $charm->created_time = time();

            $charm->create();

        }


    }


    /**
     * timed task
     * sync the rich ranking to the rich ranking table
     */
    public function sync_rich_rankingAction()
    {

        richRankingModel::DB()->query_sql("delete from rich_ranking");

        //select config
        $config = settingModel::DB()->getAll(
            "SELECT start_time,end_time,input_value,order_type FROM ql_setting WHERE id=3"
        );

        $start_time = $config[0]->start_time;
        $end_time = $config[0]->end_time;
        $limit = $config[0]->input_value;
        $order_type = $config[0]->order_type;

        if ($order_type == 'recharge') {

            $limit *= 3;

            $sql = "SELECT  money.uid AS id,money.add_time FROM money";
            $sql .= " LEFT JOIN users ON money.uid = users.id ";
            $sql .= " WHERE users.auth_state <> 2 AND type=1 AND money.add_time>$start_time AND money.add_time<$end_time ";
            $sql .= " ORDER BY money.add_time DESC LIMIT $limit";

            $base_info = moneyModel::DB()->getAll($sql);


            $base_info_new = [];
            foreach ($base_info as $k => $v) {

                $base_info_new[$v->id] = $v;
            }

            $base_info = $base_info_new;


        } else {

            $sql = "SELECT sum(value) money,money.uid AS id FROM money";
            $sql .= " LEFT JOIN users ON money.uid = users.id ";
            $sql .= " WHERE users.auth_state <> 2 AND (type=20 OR type=30) AND money.add_time>$start_time AND money.add_time<$end_time ";
            $sql .= " GROUP BY money.uid ORDER BY sum(value) ASC LIMIT $limit";

            $base_info = moneyModel::DB()->getAll($sql);
        }


        if ($order_type != 'recharge') {
            foreach ($base_info as $k => $vo) {
                $base_info_new[$vo->id] = substr($vo->money, 1);
            }
        }

        $list = usersDomain::format_simple_user_list($base_info, 'id', 1);


        foreach ($list as $v) {

            $user = usersModel::findFirst("id=".$v->id);
            if (!$user) {
                continue;
            }

            $rich = new richRankingModel();
            $rich->uid = $v->id;
            $rich->nickname = $v->nickname;
            $rich->img = $v->img;
            if ($order_type != 'recharge') {
                $rich->total_consumption = $base_info_new[$v->id];
                $rich->total_money = $base_info_new[$v->id];
            }
            $rich->location = $v->location;
            $rich->birthday = $v->birthday;
            $rich->price = $v->price;
            $rich->created_time = time();

            $rich->create();

        }


    }


    /**
     * timed task
     * add the host's today online time to the online time table
     */
    public function add_host_today_online_timeAction()
    {

        $recent_record = onlineTimeModel::findFirst(array("order" => "id desc"));

        $recent_date = strtotime(date("Y-m-d", $recent_record->date));  // get the recent date

//        $today_time = time();
//        $yesterday_time = $today_time-3600*24;


        $today_date = strtotime(date("Y-m-d", time()));


        if ($recent_date == $today_date) {
            exit("今天已经插入过了");
        }

        $host_list = usersModel::find("auth_state=2");

        $date = time();

        foreach ($host_list as $k => $v) {

            $onlineTime = new onlineTimeModel();

            $online_time_long = userTimeCountRModel::get_online_time_long($v->id);

            $onlineTime->uid = $v->id;
            $onlineTime->time_long = $online_time_long;
            $onlineTime->date = $date;


            $onlineTime->save();

        }


    }

    public function update_online_timeAction()
    {
        $time = '1506123001';
        $online_time = onlineTimeModel::find("date=".$time);
        foreach ($online_time as $k => $v) {
            $o = onlineTimeModel::findFirst("id=".$v->id);
            $online_time_long = userTimeCountRModel::get_online_time_long($v->uid);

            $o->time_long = $online_time_long + rand(100, 500);

            $o->save();

        }

    }

    public function get_online_timeAction()
    {

        $online_time = onlineTimeModel::find();

        foreach ($online_time as $k => $v) {

            $minute = intval($v->time_long / 60) * 2;
            $second = $v->time_long % 60;

            echo "uid:".$v->uid."在线时长:$minute 分 $second 秒<br/>";

        }


    }


    public function sync_redis_to_serverAction()
    {

        $users = usersModel::find();

        foreach ($users as $k => $v) {

            $userInfo = usersModel::info($v->id);

            if ($v->sex) {
                echo $v->id, "&nbsp;&nbsp;&nbsp;", $userInfo->nickname, "<br/>";
                continue;
            }

            $user = usersModel::findFirst("id=".$v->id);

            $user->nickname = $userInfo->nickname;
            $user->sex = $userInfo->sex;
            $user->birthday = $userInfo->birthday;

            $result = $user->save();

            if (!$result) {
                echo $v->id, "<br/>";
            }

        }

    }


    /**
     * calculate the recharge data to recharge_clc table
     */
    public function recharge_calcAction()
    {

        // calculate the day
        $sql = "DELETE FROM  recharge_calc;";
        rechargeCalcModel::DB()->query_sql($sql);

        $sql = "SELECT FROM_UNIXTIME(add_time,'%Y-%m-%d') days,sum(value) total_money FROM money WHERE type=1 GROUP BY days";

        $result = moneyModel::DB()->getAll($sql);

        foreach ($result as $k => $v) {

            rechargeCalcModel::create_date_calc($v->days);
        }


    }

    /**
     * calculate the recharge data to recharge_clc table by the single date
     */
    public function recharge_calc_by_dateAction()
    {

        $date = $this->request->get('date');

        if (!$date) {
            exit('请先填日期');
        }

        rechargeCalcModel::create_date_calc($date);

    }


    /**
     * copy the user table id to uid
     */
    public function sync_uidAction()
    {
        $sql = "SELECT id,uid FROM users WHERE id>244058";
        $list = usersModel::DB()->getAll($sql);
        foreach ($list as $k => $v) {
            echo $v->id, "<br/>";

            $user = usersModel::findFirst("id=".$v->id);
            $user->uid = $v->id;
            $user->save();
        }
    }

    /**
     * modify the nine long id to six number long
     */

    public function modify_users_uidAction()
    {
        $sql = "SELECT id,uid FROM users WHERE id>240889";
        $list = usersModel::DB()->getAll($sql);
        foreach ($list as $k => $v) {

            $user = usersModel::findFirst("uid=".$v->uid);
            $user->id = substr($user->id, 0, 1)."4".substr($user->id, -4);
            $sql = "UPDATE users set id=".$user->id." WHERE uid=".$user->uid;

            echo $sql, ";<br/>";

        }
    }


    /**
     * modify the nine long id to six number long
     */
    public function money_old_uidAction()
    {

        $sql = "SELECT id,uid FROM money WHERE uid>236088";
        $list = moneyModel::DB()->getAll($sql);
        foreach ($list as $k => $v) {
            echo $v->id, "<br/>";

            $money = moneyModel::findFirst("id=".$v->id);
            $money->old_uid = $v->uid;
            $money->save();
        }
    }

    /**
     * modify the nine long id to six number long
     */
    public function modify_money_uidAction()
    {

        $sql = "SELECT id,uid FROM money WHERE uid>236088";
        $list = moneyModel::DB()->getAll($sql);
        foreach ($list as $k => $v) {

            $money = moneyModel::findFirst("id=".$v->id);
            $money->uid = "24".substr($money->uid, -4);
            $sql = "UPDATE money set uid=".$money->uid." WHERE id=".$money->id;

            echo $sql, ";<br/>";

        }
    }


    public function git_shAction()
    {
        $result = shell_exec("/var/www/html/qllivef/git_hook.sh");

        $test = "ll /var/www/";
        exec($test, $array);
        var_dump($array);
    }

    public function sms_to_userAction()
    {

        $from_uid = 231829;
        $to_uid = 240004;
        $content = '你附近有好多美女在线，上来看看1';

        systemMessModel::rand_live_to_user($to_uid);

    }


    public function get_user_online_stateAction()
    {

        $uid = 231829;

        $online_state = (int)usersStateModel::get($uid);

        var_dump($online_state);

//        $value =   App::TxMess()->user_state($uid);
//        var_dump($value);

    }


    public function web_socketAction()
    {

//        phpinfo();exit;

    }

    public function swoole_clientAction()
    {
        $client = new swoole_client(SWOOLE_SOCK_TCP);

        //连接到服务器
        if (!$client->connect('111.230.2.244', 9501, 0.5)) {
            die("connect failed.");
        }
        //向服务器发送数据
        if (!$client->send("hello world")) {
            die("send failed.");
        }
        //从服务器接收数据
        $data = $client->recv();
        if (!$data) {
            die("recv failed.");
        }
        echo $data;
        //关闭连接
        $client->close();

        exit;
    }


    public function node_game1Action(){

    }

    public function node_game2Action(){

    }

    public function local_websocketAction(){

        phpinfo();exit;
    }

    public function testAction()
    {

        phpinfo();

        $uid = 240004;

        $balance = usersModel::get_balance($uid);

        var_dump($balance);
    }


    public function create_tecentAction(){

        $uid = $this->request->get('uid');

        if(!$uid){
            $uid = '231421';
        }

        $result = App::TxMess()->create_tx_user($uid,$uid, "");

        var_dump($result);

    }



    public function get_txt_user_infoAction(){

        $uid = $this->request->get('uid');

        if(!$uid){
            $uid = '231421';
        }

        usersModel::clear($uid);

        $res_data = App::TxMess()->get_user_info($uid);

        var_dump($res_data);

    }


    public function edit_tecentAction(){

        $uid = $this->request->get('uid');

        if(!$uid){
            $uid = '231421';
        }

        $nickname = 'test6666';

        $tx_user = App::TxMess()->edit_tx_user($uid, $nickname);

        var_dump($tx_user);


    }


    //     * */1 * * * /etc/init.d/php7.0-fpm restart


    public function swoole_liveAction(){

        phpinfo();

    }

    public function swoole_live2Action(){

        phpinfo();

    }

    public function minus_usersAction(){

        $sql = "SELECT sum(value) AS balance,uid FROM money   GROUP BY uid ORDER BY balance LIMIT 50";
        $result = usersModel::DB()->getAll($sql);

        echo "<br/>";
        print_r($result);

    }



}
