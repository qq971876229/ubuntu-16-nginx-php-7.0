<?php

namespace App\Controllers;

use App\Domain\usersDomain;
use App\Lib\System\App;
use App\Models\moneyModel;
use App\Models\usersModel;
use App\Models\cashNotesModel;
use App\Domain\payDomain;
use App\Models\logPayModel;


class PayController extends baseController
{


    /**
     * 21000 App Store不能读取你提供的JSON对象
     * 21002 receipt-data域的数据有问题
     * 21003 receipt无法通过验证
     * 21004 提供的shared secret不匹配你账号中的shared secret
     * 21005 receipt服务器当前不可用
     * 21006 receipt合法，但是订阅已过期。服务器接收到这个状态码时，receipt数据仍然会解码并一起发送
     * 21007 receipt是Sandbox receipt，但却发送至生产系统的验证服务
     * 21008 receipt是生产receipt，但却发送至Sandbox环境的验证服务
     */
    function acurl($receipt_data, $sandbox = 0)
    {

        //小票信息
        $POSTFIELDS = array("receipt-data" => $receipt_data);
        $POSTFIELDS = json_encode($POSTFIELDS);

        //正式购买地址 沙盒购买地址
        $url_buy = "https://buy.itunes.apple.com/verifyReceipt";
        $url_sandbox = "https://sandbox.itunes.apple.com/verifyReceipt";
        $url = $sandbox ? $url_sandbox : $url_buy;

        //简单的curl
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $POSTFIELDS);
        $result = curl_exec($ch);


        // 返回最后一次的错误号
        $errno = curl_errno($ch);

        if ($errno) {
            App::Input()->error("curl 错误".$errno);
        }


        curl_close($ch);

        return $result;
    }


    public function IAPAction()
    {

        $uid = App::Auth()->authLogin();


        //用户发来的参数
        $receipt_data = App::Input()->get("data");

        $order = App::Input()->get("order");

        //验证参数
        if (strlen($receipt_data) < 20) {
            App::Input()->error("非法参数");
        }

        //请求验证
        $html = $this->acurl($receipt_data);


        $data = json_decode($html, 1);

        //如果是沙盒数据 则验证沙盒模式
        if ($data['status'] == '21007') {
            //请求验证
            $html = $this->acurl($receipt_data, $sandbox = 1);
            $data = json_decode($html, 1);
            $data['sandbox'] = '1';
        }

        if ($data['status'] == 0) {
            if (logPayModel::paid_iap($order, $uid, "BIAP") == false) {
                App::Input()->error("订单不存在");
            }


            $user_info = new usersDomain($uid);

            $user = $user_info->full_info();

            App::Input()->out(array("uubean" => $user->uubean));

        } else {
            App::Input()->error($data['status']);
        }

    }


    public function cash_listAction()
    {
        $uid = App::Auth()->authLogin();
        $page = App::Input()->get("page");

        App::Input()->out(cashNotesModel::cash_list($uid, $page));

    }


    public function money_detailsAction()
    {
        $uid = App::Auth()->authLogin();

        $page = App::Input()->get_page();

        $money = new moneyModel();
        $money->set_uid($uid);


        App::Input()->out($money->getlist($page));
    }

    public function money_month_detailsAction()
    {
        $uid = App::Auth()->authLogin();

        $page = App::Input()->get_page();

        $money = new moneyModel();
        $money->set_uid($uid);


        App::Input()->out($money->get_month_list($page));
    }

    public function money_week_detailsAction()
    {
        $uid = App::Auth()->authLogin();

        $page = App::Input()->get_page();

        $money = new moneyModel();
        $money->set_uid($uid);


        App::Input()->out($money->get_week_list($page));
    }


    public function money_totalAction()
    {
        $uid = App::Auth()->authLogin();


        App::Input()->out(moneyModel::get_user_money($uid));
    }


    public function accountAction()
    {
        $uid = App::Auth()->authLogin();

        $cash_account = App::Input()->get("cash_account");
        $cash_name = App::Input()->get("cash_name");

        usersModel::set_cash_account($uid, $cash_account, $cash_name);

        App::Input()->out("ok");
    }


    public function cashAction()
    {

        $uid = App::Auth()->authLogin();
        $money = App::Input()->get("money");

//        $uid = $this->request->getPost("uid");
//        $money = $this->request->getPost("money");


        $money = intval($money);

        // Determine whether the user has withdrawn or not
        $today_time = strtotime(date("Y-m-d",time()));

        $sql = "SELECT uid FROM cash_notes WHERE uid=$uid AND add_time>=".$today_time;

        $already_cash_today = cashNotesModel::DB()->getOne($sql);

        if($already_cash_today){
            App::Input()->error("一天只能提现一次");
            exit;
        }

        if ($money % 100 != 0) {

            App::Input()->error("提现金额必须为100的整数倍");
            exit;
        }


        $pay = new payDomain();
        $pay->setkey($uid);
        $pay->cash($money);
        App::Input()->out("ok");

    }


    public function pay_typeAction()
    {
        //App::Input()->out(array("微信"=>"wx","苹果内购"=>"BIAP"));
        App::Input()->out(array("微信" => "wx"));
    }


    public function rechargeAction()
    {


        require APP_PATH.'/lib/pingxx/pay.php';


        $uid = App::Auth()->authLogin();

        $code = App::Input()->get("code");
        $money = App::Input()->get("money");


        if ($code == "BIAP") {
            $order_no = logPayModel::add($uid, $money, $code);

            App::Input()->out($order_no);
        }


        $order_no = logPayModel::add($uid, $money, $code);

        $mess = get_buy_code($code, $money, "充值".$money."人民币", $order_no);

        App::Input()->out($mess);
    }


    public function webhooksAction()
    {
        /*
        $order_no = "bxcz114905863566271";
        $money = "7";
        $r = logPayModel::paid($order_no, $money);
        echo $r;*/

        //

        require APP_PATH.'/lib/pingxx/webhooks.php';


    }

    public function messAction()
    {
        $uid = App::Auth()->authLogin();
        $id = App::Input()->get("id");

        payDomain::pay_mess($uid, $id);

        App::Input()->out("ok");
    }

    public function infoAction()
    {
        $uid = App::Auth()->authLogin();

        $user = new usersDomain($uid);

        App::Input()->out($user->get_cash_account());


    }

    public function get_info_by_uidAction()
    {

        $uid = '231582';
        $user = new usersDomain($uid);

        App::Input()->out($user->get_cash_account());
    }


}

