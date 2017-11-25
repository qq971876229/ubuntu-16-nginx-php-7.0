<?php

namespace App\RModels;


use App\Domain\usersDomain;
use App\Models\usersModel;
use App\Models\logGiftModel;

class userTimeCountRModel extends \bcl\redis\objectBase
{

    /**
     * set the user's online time,add 30 seconds when percent request
     * @param $uid
     */
    public static function count($uid)
    {


        $time = time();
        $path = "user_time_count:".date('Ymd', $time).":".$uid;

        $info = self::get_rd()->get($path);

        if ($info == false) {
            $info = new \stdClass();
            $info->total_time = 30;
            $info->cur_time = time();
        } else {
            $info = json_decode($info);
        }


        if (time() < $info->cur_time + 30) {
            $info->total_time = $info->total_time + ($time - $info->cur_time);
            $info->cur_time = $time;

        } else {
            $info->total_time = $info->total_time + 30;
            $info->cur_time = $time;
        }


        self::get_rd()->set($path, json_encode($info));
    }


    /**
     * return the  home index calculate online time long
     * @return \stdClass
     */
    public static function get_index_info()
    {

        $time = time();

        $path = "user_time_count:".date('Ymd', $time).":*";

        // get the all users redis time count key
        $list = self::get_rd()->getKeys($path);

        $user_list = [];

        foreach ($list as $v) {

            // calculate the uid by the path, the key is the uid
            $key = substr($v, strlen($path) - 1, strlen($v) - strlen($path) + 1);

            $info = self::get_rd()->get("user_time_count:".date('Ymd', $time).":".$key);

            $user = usersModel::info($key);

            if($user){
                $live = $user->is_live;
            }else{
                $sql = "SELECT auth_state FROM users WHERE id=".$key;
                $auth_state = usersModel::DB()->getOne($sql);
                if($auth_state==2){
                    $live = 1;
                }else{
                    $live = 0;
                }
            }

            $r = json_decode($info);

            $r->uid = $key;

            $r->is_live = $live;

            $user_list[] = $r;
        }

        $data = new \stdClass();
        $data->view_online_num = 0;
        $data->live_online_num = 0;

        $data->view_online_time = 0;
        $data->live_online_time = 0;


        $data->view_register_num = 0;
        $data->live_register_num = 0;
        $data->live_money = 0;

        foreach ($user_list as $v) {

            if ($v->is_live == 1) {
                $data->live_online_num++;
                $data->live_online_time = $data->live_online_time + $v->total_time;
            } else {
                $data->view_online_num++;
                $data->view_online_time = $data->view_online_time + $v->total_time;
            }

        }

        if ($data->live_online_num == 0) {

            $data->live_online_time = 0;
        } else {

            $data->live_online_time = floor($data->live_online_time / $data->live_online_num);
        }


        if ($data->view_online_num == 0) {

            $data->view_online_time = 0;
        } else {

            $data->view_online_time = floor($data->view_online_time / $data->view_online_num);
        }


        $today = strtotime(date("Y-m-d"), $time);

        $list = usersModel::find("add_time>$today");

        foreach ($list as $v) {

            if ($v->auth_state == 2) {
                $data->live_register_num++;
            } else {
                $data->view_register_num++;
            }

        }


        $list = logGiftModel::find("add_time>$today");

        foreach ($list as $v) {

            $data->live_money = $data->live_money + $v->money;
        }

        return $data;


    }




    /**
     * get the user's online time
     * @param $uid
     * @return int
     */
    public static function get_online_time_long($uid)
    {

        $time = time();

        $info = self::get_rd()->get("user_time_count:".date('Ymd', $time).":".$uid);

        if ($info) {

            $info_array = json_decode($info, true);

            $online_time_long = $info_array['total_time'];
        } else {
            $online_time_long = 0;
        }

        return $online_time_long;


    }




}
