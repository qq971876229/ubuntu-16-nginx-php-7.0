<?php

namespace App\Domain;

use App\Models\usersModel;

use App\Models\xpUserModel;
use App\Models\rankUserModel;
use App\Models\moneyModel;
use App\Models\usersStateModel;
use App\Lib\System\App;
use App\Models\vodSessionModel;


/*
{
    "xp_user": 0,
    "rank_user": 1,
    "uumoney": 0,
    "uubean": "7",
    "get_uubean": 0,
    "give_uubean": 0,
    "is_real_name": 0,
    "real_name": "",
    "moblie": "",
    "verified_reason": ""
}
*/

class usersDomain extends baseDomain
{


    public function __construct($uid)
    {
        $this->setkey((string)$uid);
    }


    public static function random($num)
    {

        $value = usersModel::DB()->getAll("select id from users  where auth_state = 2 ORDER BY RAND() LIMIT 0,$num");


        $list = usersDomain::format_simple_user_list($value, "id");


        usort(
            $list,
            function ($a, $b) {

                if ($a->online_state == $b->online_state) {
                    return 0;
                }

                if ($a->online_state > $b->online_state) {
                    return -1;
                }

                if ($a->online_state < $b->online_state) {
                    return 1;
                }

            }
        );


        return $list;

    }


    public static function format_simple_user_list($list, $key = 'uid', $no_host_type = 0)
    {


        $res_list = [];
        if ($key == null) {


            foreach ($list as $v) {


                $user = new usersDomain($v);

                $res_list[] = $user->simple_info();
            }

        }


        foreach ($list as $k => $v) {

            $user = new usersDomain($list[$k]->$key);


            $user = $user->simple_info();

            if ($no_host_type == 0 && $user ) {

                $host_type_sql = "SELECT host_type FROM users WHERE id=".$user->id;
                $host_type = usersModel::DB()->getOne($host_type_sql);
                $stick = usersModel::DB()->getOne("SELECT stick FROM users WHERE id=".$user->id);
            }else{
                $host_type = '';
                $stick = '';
            }



            if ($user == false) {
                $user = new \stdClass();

                $user->nickname = "已删除";
                $user->img = "";
                $user->sex = "";
                $user->online_state = 0;;
                $user->answer_rate = 0;;
                $user->price = 0;;
                $user->rank_user = 0;;
                $user->is_live = 0;
                $user->location = "";
                $user->signature = "";
                $user->birthday = 0;
                if ($no_host_type) {

                    $user->host_type = "";
                    $user->stick = 0;
                }
            }


            $v->nickname = $user->nickname;
            $v->img = $user->img;
            $v->sex = $user->sex;
            $v->online_state = $user->online_state;
            $v->answer_rate = $user->answer_rate;
            $v->price = $user->price;
            $v->rank_user = $user->rank_user;
            $v->is_live = $user->is_live;
            $v->location = $user->location;
            $v->signature = $user->signature;
            $v->birthday = $user->birthday;


            if ($no_host_type == 0 ) {

                $v->host_type = $host_type;
                $v->stick = $stick;
            }

            $res_list[] = $v;
        }


        foreach ($res_list as $v) {


            unset($v->cash_account);
            unset($v->constellation);
            unset($v->cash_name);
            unset($v->seat);
            unset($v->xp_user);
            unset($v->money);
            unset($v->auth_state);
            unset($v->salt);
            unset($v->pass);

        }


        return $res_list;

    }


    public function get_user_extend($user_info)
    {

        $data = new \stdClass();

        $data->rank_user = rankUserModel::get_user_rank($user_info);

//        $data->money = moneyModel::get_value($this->getKey());
        $data->money = usersModel::get_balance($this->getKey());

        $data->cash_money = moneyModel::get_cash_value($this->getKey());

        $data->answer_rate = vodSessionModel::get_answer_rate($this->getKey());

        $data->online_state = (int)usersStateModel::get($this->getKey());

        // select the online state from the table
        $request_time = usersModel::DB()->getAll(
            "SELECT request_time,online_state FROM users WHERE id=".$user_info->id
        );

//        $data->online_state = $request_time[0]->online_state;

        $request_time = $request_time[0]->request_time;

        $leave_time = time() - $request_time;

        if ($leave_time < 600) {

            if ($data->online_state == 30) {

                $data->online_state = 30; // busy
            } else {

                $data->online_state = 40;
            }


        } else {
            if ($data->online_state == 30) {

                $data->online_state = 30; // busy
            } else {
                if ($user_info->is_disturb) {
                    $data->online_state = 20;
                } else {
                    $data->online_state = 10;
                }

            }
        }


        return $data;

    }


    public function simple_info()
    {
        $data = $this->full_info();


        unset($data->money);

        return $data;
    }


    public function get_cash_account()
    {

        $uid = $this->getKey();

        $user_info = usersModel::findFirst("id = '$uid'");

        if ($user_info == false) {
            return;
        }

        $data = new \stdClass();

        $data->money = $this->full_info()->money;

        $data->cash_account = $user_info->cash_account;
        $data->cash_name = $user_info->cash_name;

        if (strlen($data->cash_account) < 2) {
            $data->cash_account = "";
        }

        if (strlen($data->cash_name) < 2) {
            $data->cash_name = "";
        }

        return $data;


    }


    public function full_info()
    {

        //-----基本-------

        $user_info = usersModel::info($this->getKey());


        if ($user_info == false) {
            return false;
        }

        $res_data = new \stdClass();

        $user_extend = $this->get_user_extend($user_info);

        foreach ($user_info as $k => $v) {
            $res_data->$k = $v;
        }

        foreach ($user_extend as $k => $v) {
            $res_data->$k = $v;
        }


        if ($res_data->online_state == \configLive::user_online_state['free'] && $res_data->is_disturb == 1) {
            $res_data->online_state = \configLive::user_online_state['disturb'];
        }


        return $res_data;


    }


    public function exists()
    {
        $user_info = usersModel::info($this->getKey());

        if ($user_info == false) {
            return false;
        }

        return true;
    }


    public function get_reference()
    {
        $uid = $this->getKey();
        $user_info = usersModel::findFirst("id = '$uid'");

        if ($user_info == false) {
            return false;
        }


        if ($user_info->reference == 0) {
            return false;
        }


        if ($user_info->reference_time < time()) {
            return false;
        }


        $reference_info = usersModel::info($user_info->reference);

        if ($reference_info == false) {
            return false;
        }


        //1521388638
        return $user_info->reference;

    }


}
