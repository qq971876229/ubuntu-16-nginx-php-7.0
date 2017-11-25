<?php

namespace App\Models;

use Phalcon\Mvc\Model;
use Phalcon\Mvc\Model\Resultset\Simple as Resultset;
use App\App;
use App\Models\usersModel;
use Phalcon\Mvc\Model\Behavior\Timestampable;
use phpDocumentor\Reflection\Types\Integer;
use App\Lib\System\Transaction;
use App\RModels\logRModel;
use App\Domain\usersDomain;

class logPayModel extends baseModel
{




    public function getSource()
    {
        return "log_pay";
    }



    public static function recharge_list_pc($page)
    {


        $sql = "select * from log_pay where is_paid=1 ";

        if($page['start_time']){
            $sql .= " AND add_time>=".$page['start_time'];
        }

        if($page['end_time']){
            $sql .=" AND add_time<=".$page['end_time'];
        }

        $sql .= " order by id desc";


        $list = self::page_pc($page, $sql);

        foreach ($list['list'] as $k => $v) {
            $list['list'][$k]->money_value = $list['list'][$k]->money;
        }

        $list['list'] = usersDomain::format_simple_user_list($list['list'], "uid");

        foreach ($list['list'] as $k => $v) {

            $list['list'][$k]->img = \config::app['img_bucket_url'].$list['list'][$k]->img;
            $list['list'][$k]->paid_time = date("Y-m-d H:i", $list['list'][$k]->paid_time);

            if ($list['list'][$k]->code == "wx") {
                $list['list'][$k]->code_name = "微信";
            }

            if ($list['list'][$k]->code == "alipay") {
                $list['list'][$k]->code_name = "支付宝";
            }

        }


        return $list;

    }

    public static function total_recharge($start_time,$end_time){

        $sql = "SELECT sum(money) FROM log_pay WHERE is_paid=1 ";

        if($start_time){
            $sql .= " AND add_time>=".strtotime($start_time);
        }

        if($end_time){
            $sql .=" AND add_time<=".strtotime($end_time);
        }

        $num = logPayModel::DB()->getOne($sql);
        if(!$num){
            $num = 0;
        }

        return $num;

    }

    public static function paid_iap($order_no,$uid,$code)
    {

        $pay =  logPayModel::findFirst("order_no='$order_no'");

        if($pay == false)
        {
            return false;
        }

        if($pay->uid != $uid)
            return false;


         if($pay->code != $code)
         {
             return false;
         }


        if($pay->is_paid == 1)
        {
            return true;
        }

        $pay->is_paid =1;

        $pay->paid_time = time();

        $transaction = new Transaction();


        $transaction->run(function()use($transaction,$pay)
        {


            $pay->save();

            $uubeanModel = new uubeanModel();
            $uubeanModel->set_uid($pay->uid);

            $transaction->set_commit($uubeanModel);

            $money_record = $uubeanModel->
            insert($pay->money*7,"充值",$pay->order_no,\configPay::bean_type['recharge']);

            return true;

        },"pay:".$pay->uid);




        return true;


    }

    public static function paid($order_no,$money)
    {


        logRModel::log("webhooks", "paid");

       $pay =  logPayModel::findFirst("order_no='$order_no'");

       if($pay == false)
       {
           return "订单获取不到";
       }


       if($pay->is_paid == 1)
       {
           return "已记账";
       }


       logRModel::log("webhooks", "已记账");


         if($pay->money*\config::debug['recharge_multiple'] != $money)
         {
             return "金额不符";
         }

         logRModel::log("webhooks", "金额不符");


           $pay->is_paid =1;

           $pay->paid_time = time();


           //--------------------
           /*
           App::begin();

           $pay->save();

           uubeanModel::recharge($pay->uid, $pay->money*7, $pay->order_no);

           App::commit();

           usersModel::update_uubean_info($pay->uid);
           */

           $transaction = new Transaction();


           $transaction->run(function()use($transaction,$pay)
           {


               //logRModel::log("webhooks", "transaction");

               $pay->save();

               $moneyModel = new moneyModel();
                $moneyModel ->set_uid($pay->uid);

               $transaction->set_commit( $moneyModel);

               //logRModel::log("webhooks", "transaction1");




               $money_record =  $moneyModel->
               insert($pay->money,"充值",$pay->order_no,\configPay::money_type['recharge']);


               //logRModel::log("webhooks", "transaction2");

               return true;

           },"pay:".$pay->uid);


           //-------------

           return "ok";
    }

    public static function add($uid,$money,$code)
    {
        $pay = new logPayModel();

         $order_no = \config::app['app_name'].$uid.time().rand(1000,9999);

        $pay->code = $code;

        $pay->uid = $uid;

        $pay->money = $money;

        $pay->order_no = $order_no;

        $pay->add_time = time();

        $pay->save();

        return $order_no;

    }


    public static function get($uid)
    {

        $value = self::DB()->getOne("select sum(money) from log_pay where uid=? and is_paid=1",array($uid));


        if($value == false)
            return 0;

        return $value;
    }

}
