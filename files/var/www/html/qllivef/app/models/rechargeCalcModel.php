<?php
/**
 * Created by PhpStorm.
 * User: wxx
 * Date: 2017/9/10
 * Time: 下午9:49
 */

namespace App\Models;

class rechargeCalcModel extends baseModel
{

    public function getSource()
    {
        return 'recharge_calc';
    }

    public static function create_date_calc($date)
    {

        if (!$date) {
            exit('请先填日期');
        }
        // calculate the day
        $sql = "DELETE FROM recharge_calc WHERE date='$date';";
        rechargeCalcModel::DB()->query_sql($sql);

        // select this day's recharge people number
        $today_start_time = strtotime($date);
        $today_end_time = strtotime($date.'23:59');

        $sql_num = "SELECT count(distinct(uid)) FROM money WHERE type=1 AND add_time>=".$today_start_time." AND add_time<=".$today_end_time;

        $today_recharge_num = moneyModel::DB()->getOne($sql_num);

        $calc = new rechargeCalcModel();
        $calc->num = $today_recharge_num;
        $calc->date = $date;

        // register number
        $calc->register = usersModel::get_register_by_date($date);

        // ios register number
        $calc->register_ios = usersModel::get_register_by_date($date, 'ios');

        // android register number
        $calc->register_android = usersModel::get_register_by_date($date, 'android');

        // login view number
        $calc->login_view = logLoginModel::get_login_by_date($date, 'view');

        // login view number
        $calc->login_live = logLoginModel::get_login_by_date($date, 'live');

        // get the reference_money
        $calc->reference_money = moneyModel::get_reference_recharge_by_date($date);

        // get the total money
        $calc->total_money = moneyModel::get_total_recharge_by_date($date);

        // get the first
        $calc->first = moneyModel::get_first_recharge_people_num_by_date($date);

        // get the ios
        $calc->ios = moneyModel::get_ios_recharge_by_date($date);

        // get the android
        $calc->android = moneyModel::get_android_recharge_by_date($date);

        //average_money
        if ($today_recharge_num != 0) {
            $average_money = $calc->total_money / $today_recharge_num;
        } else {
            $average_money = 0;
        }
        $calc->average_money = $average_money;

        //total_order
        $calc->total_order = moneyModel::get_total_order_num_by_date($date);

        //ten
        $calc->ten = moneyModel::get_order_num_by_date_and_type($date, 10);

        //thirty
        $calc->thirty = moneyModel::get_order_num_by_date_and_type($date, 30);

        //fifty
        $calc->fifty = moneyModel::get_order_num_by_date_and_type($date, 50);

        //one_hundred
        $calc->one_hundred = moneyModel::get_order_num_by_date_and_type($date, 100);

        //two_hundred
        $calc->two_hundred = moneyModel::get_order_num_by_date_and_type($date, 200);

        //five_hundred
        $calc->five_hundred = moneyModel::get_order_num_by_date_and_type($date, 500);

        //one_thousand
        $calc->one_thousand = moneyModel::get_order_num_by_date_and_type($date, 1000);

        //two_thousand
        $calc->two_thousand = moneyModel::get_order_num_by_date_and_type($date, 2000);

        //update time
        $calc->updated_time = time();

        $calc->create();


    }

}