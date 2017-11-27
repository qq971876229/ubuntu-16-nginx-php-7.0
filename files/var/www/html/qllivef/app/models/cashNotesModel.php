<?php

namespace App\Models;

use Phalcon\Mvc\Model;
use App\Bll\API;
use Phalcon\Mvc\Model\Resultset\Simple as Resultset;
use App\Bll\Input;
use App\Lib\System\App;
use App\Lib\System\Transaction;
use Phalcon\Mvc\Model\Behavior\Timestampable;
use App\Domain\usersDomain;

//提现
class cashNotesModel extends baseModel
{


    public function getSource()
    {
        return "cash_notes";
    }


    public static function check_cash($id, $remark)
    {

        $cash = cashNotesModel::findFirst("id='$id'");

        if ($cash == false) {
            App::Input()->error("记录不存在");
        }

        if ($cash->is_paid != 0) {
            App::Input()->error("不能审核");
        }


        $cash->is_paid = 1;

        $cash->admin_remark = $remark;


        $transaction = new Transaction();

        $transaction->run(
            function () use ($transaction, $cash) {

                $user_money = new moneyModel();
                $user_money->set_uid($cash->uid);


                $user_money->cash($cash->money, $cash->id);


                $cash->save();

                return true;


            },
            "pay:".$cash->uid
        );


        usersModel::clear($cash->uid);

    }


    public static function cancel_cash($id, $remark)
    {
        $cash = cashNotesModel::findFirst("id='$id'");

        if ($cash == false) {
            App::Input()->error("记录不存在");
        }

        if ($cash->is_paid != 0) {
            App::Input()->error("不能取消");
        }

        $cash->is_paid = 2;

        $cash->admin_remark = $remark;

        $cash->save();

        usersModel::clear($cash->uid);
    }


    public static function cash_list_mgr($page, $type)
    {

        if ($page == 0) {

            $list = baseModel::DB()->getAll
            (
                "select * from cash_notes  where is_paid  = ?  order by id desc limit 0,10",
                array($type)
            );

        } else {
            $list = baseModel::DB()->getAll
            (
                "select * from cash_notes  where is_paid  = ? and id <$page order by id desc limit 0,10",
                array($type)
            );
        }


        usersDomain::format_simple_user_list($list);


        foreach ($list as $k => $v) {


            if ($list[$k]->is_paid == 0) {
                $list[$k]->state = "申请中";

            } else {
                if ($list[$k]->is_paid == 1) {
                    $list[$k]->state = "已完成";
                } else {
                    if ($list[$k]->is_paid == 2) {
                        $list[$k]->state = "已取消";
                    }
                }
            }


            $info = json_decode($list[$k]->account);

            $list[$k]->cash_account = $info->cash_account;
            $list[$k]->cash_name = $info->cash_name;

            $list[$k]->add_time = date("Y-m-d", $v->add_time);

            unset($list[$k]->account);
        }

        return $list;
    }


    public static function cash_list($uid, $page)
    {
        $page = cashNotesModel::page($page, "select * from cash_notes where uid=? order by id desc", array($uid));

        foreach ($page as $k => $v) {
            unset($page[$k]->account);

            if ($page[$k]->is_paid == 0) {
                $page[$k]->state = "申请中";

            } else {
                if ($page[$k]->is_paid == 1) {
                    $page[$k]->state = "已完成";
                } else {
                    if ($page[$k]->is_paid == 2) {
                        $page[$k]->state = "已取消";
                    }
                }
            }

        }

        return $page;
    }


    /**
     * get the total number of the withdrawal
     * @param $uid
     * @return int
     */
    public static function get_total_withdrawal($uid)
    {

        $sql = "SELECT sum(money) FROM cash_notes WHERE uid=$uid";
        $total_withdrawal = cashNotesModel::DB()->getOne($sql);
        if (!$total_withdrawal) {
            $total_withdrawal = 0;
        }

        return $total_withdrawal;


    }


    public static function get_cash_list_pc($page, $type)
    {
        $list = self::page_pc($page, "select * from cash_notes where is_paid=? order by id desc", [$type]);

        foreach ($list['list'] as $k => $v) {
            $list['list'][$k]->money_value = $list['list'][$k]->money;
            $list['list'][$k]->balance = moneyModel::get_balance($v->uid);
        }


        $list['list'] = usersDomain::format_simple_user_list($list['list'], "uid",1);

        foreach ($list['list'] as $k => $v) {
            $list['list'][$k]->add_time = date("Y-m-d H:i", $list['list'][$k]->add_time);


            if ($list['list'][$k]->is_paid == 0) {
                $list['list'][$k]->state_name = "申请中";
            }

            if ($list['list'][$k]->is_paid == 2) {
                $list['list'][$k]->state_name = "已驳回";
            }

            if ($list['list'][$k]->is_paid == 1) {
                $list['list'][$k]->state_name = "已通过";
            }

            $user_info = new usersDomain($list['list'][$k]->uid);

            $list['list'][$k]->cash_info = $user_info->get_cash_account();
        }


        return $list;

    }


}
