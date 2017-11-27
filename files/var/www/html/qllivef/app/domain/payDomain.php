<?php

namespace App\Domain;

use App\Models\moneyModel;
use App\Lib\System\Transaction;
use App\Lib\System\App;
use App\Models\cashNotesModel;
use App\Models\logGiftModel;
use App\Models\usersModel;
use App\Models\vodSessionModel;


class payDomain extends baseDomain
{


    public static function pay_mess($uid, $code)
    {

        $transaction = new Transaction();


        $transaction->run(
            function () use ($transaction, $uid, $code) {


                $uumoney = new moneyModel();
                $uumoney->set_uid($uid);

                $uumoney->insert(-0.1, "消息扣费", $code, \configPay::money_type['mess']);

                return true;

            },
            "pay:".$uid
        );


    }


    /**
     * pay the money to the live when close the vod session
     * @param $live
     */
    public static function pay_live($live)
    {


        if ($live->money == 0) {
            return;
        }

        $transaction = new Transaction();

        $transaction->run(
            function () use ($transaction, $live) {

                $logGiftModel = new logGiftModel();
                $logGiftModel->set_uid($live->view_id, $live->live_id);

                $transaction->set_commit($logGiftModel);

                $gift_record = $logGiftModel->add($live->money, "视频通话", time(), $live->id, 0);


                $uumoney = new moneyModel();
                $uumoney->set_uid($live->view_id);

                $value = $live->money;

                $uumoney->insert(-$value, "与主播".$live->live_id."视频扣费", $live->id, \configPay::money_type['view_live']);

                $uumoney = new moneyModel();
                $uumoney->set_uid($live->live_id);

                $value = round($live->money * \config::pay['rate'], 2);

                $uumoney->insert($value, "与用户".$live->view_id."视频收入", $live->id, \configPay::money_type['live_live']);


                $transaction->set_commit($uumoney);

                $uid = $live->view_id;
                $live_id = $live->live_id;
                $remark = "视频通信，用户".$uid."主播".$live_id;
                $total_money = $live->money;

                //观众推荐
                $view_reference = new usersDomain($uid);
                $live_reference = new usersDomain($live_id);


                $view_reference_id = $view_reference->get_reference();
                if ($view_reference_id == $uid) {
                    $view_reference_id = false;
                }

                $live_reference_id = $live_reference->get_reference();


                if ($view_reference_id != false) {

                    $remark = "视频通信，推荐用户".$uid."主播".$live_id;


                    $view_reference_money = new moneyModel();
                    $view_reference_money->set_uid($view_reference_id);

                    $transaction->set_commit($view_reference_money);

                    //the talk reference money,if the view use money
                    $view_reference_money->insert(
                        $total_money * \config::pay['reference_view'],
                        $remark."推荐奖励",
                        $uid,
                        \configPay::money_type['reference']
                    );

                }


                if ($live_reference_id != false) {

                    $remark = "视频通信，用户".$uid."推荐主播".$live_id;

                    $live_reference_money = new moneyModel();
                    $live_reference_money->set_uid($live_reference_id);

                    $live_reference_money->insert(
                        $total_money * \config::pay['reference'],
                        $remark."推荐奖励",
                        $live_id,
                        \configPay::money_type['reference']
                    );


                    $transaction->set_commit($live_reference_money);

                }


                return true;

            },
            "pay:".$live->view_id
        );
    }


    /**
     * pay the money to the user's reference and live's reference
     * @param $live
     */
    public static function pay_reference($live)
    {

        if ($live->money == 0) {
            return;
        }

        $transaction = new Transaction();

        $transaction->run(
            function () use ($transaction, $live) {

                $logGiftModel = new logGiftModel();
                $logGiftModel->set_uid($live->view_id, $live->live_id);

                $transaction->set_commit($logGiftModel);

                $gift_record = $logGiftModel->add($live->money, "视频通话", time(), $live->id, 0);


                $uid = $live->view_id;
                $live_id = $live->live_id;
                $remark = "视频通信，用户".$uid."主播".$live_id;
                $total_money = $live->money;

                //观众推荐
                $view_reference = new usersDomain($uid);
                $live_reference = new usersDomain($live_id);


                $view_reference_id = $view_reference->get_reference();
                if ($view_reference_id == $uid) {
                    $view_reference_id = false;
                }

                $live_reference_id = $live_reference->get_reference();


                if ($view_reference_id != false) {

                    $remark = "视频通信，推荐用户".$uid."主播".$live_id;


                    $view_reference_money = new moneyModel();
                    $view_reference_money->set_uid($view_reference_id);

                    $transaction->set_commit($view_reference_money);

                    //the talk reference money,if the view use money
                    $view_reference_money->insert(
                        $total_money * \config::pay['reference_view'],
                        $remark."推荐奖励",
                        $uid,
                        \configPay::money_type['reference']
                    );

                }


                if ($live_reference_id != false) {

                    $remark = "视频通信，用户".$uid."推荐主播".$live_id;

                    $live_reference_money = new moneyModel();
                    $live_reference_money->set_uid($live_reference_id);

                    $live_reference_money->insert(
                        $total_money * \config::pay['reference'],
                        $remark."推荐奖励",
                        $live_id,
                        \configPay::money_type['reference']
                    );


                    $transaction->set_commit($live_reference_money);

                }


                return true;

            },
            "pay:".$live->view_id
        );
    }


    /**
     * pay the money to the live when in vod session every minute
     * @param $live
     */
    public static function pay_live_minute($live)
    {


        if ($live->money == 0) {
            return;
        }

        $transaction = new Transaction();


        $transaction->run(
            function () use ($transaction, $live) {


                $money_session = moneyModel::findFirst("session_id=".$live->id);


                // the first minute
                if (!$money_session) {

                    $uumoney = new moneyModel();
                    $uumoney->set_uid($live->view_id);

                    $value = $live->price;

                    $uumoney->insert(
                        -$value,
                        "与主播".$live->live_id."视频扣费",
                        $live->id,
                        \configPay::money_type['view_live'],
                        $live->id
                    );

                    $uumoney = new moneyModel();
                    $uumoney->set_uid($live->live_id);

                    $value = round($live->price * \config::pay['rate'], 2);

                    $uumoney->insert(
                        $value,
                        "与用户".$live->view_id."视频收入",
                        $live->id,
                        \configPay::money_type['live_live'],
                        $live->id
                    );

                    $transaction->set_commit($uumoney);
                } else {

                    $value = vodSessionModel::get_live_money($live);

                    // don't let the user's balance smaller than zero   ===== start
//                    $vod_max_money = usersModel::get_vod_max_money($live->view_id);
//
//                    if ($value > $vod_max_money) {
//                        $value = $vod_max_money;
//                    }
                    // don't let the user's balance smaller than zero   ===== end

                    $uumoney = new moneyModel();
                    $uumoney->set_uid($live->view_id);

                    $uumoney->update_money($live->view_id, $live->id, -$value);

                    $uumoney = new moneyModel();
                    $uumoney->set_uid($live->live_id);

                    $value = round($value * \config::pay['rate'], 2);

                    $uumoney->update_money($live->live_id, $live->id, $value);

                    $transaction->set_commit($uumoney);

                    // update the cache  ==== start
                    $user_balance = usersModel::get_balance($live->view_id);
                    $user_balance -= $live->price;
                    usersModel::update_balance($live->view_id,$user_balance);
                    // update the cache  ==== end

                }

                return true;

            },
            "pay:".$live->view_id
        );
    }

    public static function pay_debug_add_money($uid, $money)
    {
        $transaction = new Transaction();

        $transaction->run(
            function () use ($transaction, $uid, $money) {

                $uumoney = new moneyModel();
                $uumoney->set_uid($uid);


                $uumoney->insert($money, "调试加钱", time(), \configPay::money_type['debug']);

                return true;

            },
            "pay:".$uid
        );

    }

    public function cash($cash_money)
    {


        if ($cash_money < 100) {
            App::Input()->error("提现金额需要大于100");
        }


        $uid = $this->getKey();

        $user = new usersDomain($uid);

        $user_info = $user->get_cash_account();


        if (strlen($user_info->cash_account) < 2 || strlen($user_info->cash_name) < 2) {
            App::Input()->error("没有设置提现账号");
        }

        if ($cash_money > $user->full_info()->money) {
            App::Input()->error("余额不足");
        }


        $cash = new cashNotesModel();
        $cash->uid = $uid;
        $cash->money = $cash_money;
        $cash->add_time = time();
        $cash->save();

        usersModel::clear($uid);

    }


}
