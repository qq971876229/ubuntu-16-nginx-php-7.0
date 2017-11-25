<?php

namespace App\Controllers\mgr;

use App\Lib\System\App;
use App\Models\logPayModel;
use App\Models\moneyModel;
use App\Models\rechargeCalcModel;
use App\Models\usersMgrModel;
use App\RModels\userTimeCountRModel;

class HomeController extends baseController
{

    public function loginAction()
    {

        $user = $this->request->getPost("user_name");
        $pass = $this->request->getPost("user_pass");

        $this->view->name = \config::app['app_name_cn'];

        $this->view->mess = "";

        if (strlen($user) < 1) {
            return;
        }


        $user_info = usersMgrModel::check_pass($user, $pass);


        if ($user_info == false) {
            $this->view->mess = "账号或密码错误";

            return;
        } else {

            $this->session->set("user_info", $user_info);


            $this->response->redirect('/index.php?_url=/mgr/home/menu');
        }


    }

    public function menuAction()
    {

        $this->isAuth();
        $this->app_name();
    }

    public function indexAction()
    {

        $this->isAuth();
        $this->app_name();

        $home = $this->request->get("home");

        if ($home == 1) {

//            $data = userTimeCountRModel::get_index_info();

            $date = date("Y-m-d",time());
            $calc = rechargeCalcModel::findFirst("date='".$date."'");

//            var_dump($calc->toArray());exit;
//            $today_first_recharge_num = moneyModel::get_today_first_recharge_num();

//            $today_first_recharge_num = moneyModel::get_today_first_recharge_num();

//            $start_time = date("Y-m-d", time());
            $today_total_recharge_num = moneyModel::get_total_recharge_by_date($date);

            $this->view->calc = $calc;
//            $this->view->data = $data;
//            $this->view->today_first_recharge_num = $today_first_recharge_num;
            $this->view->today_total_recharge_num = $today_total_recharge_num;
        } else {

            $this->response->redirect('/index.php?_url=/mgr/setting/index');
        }

    }

    public function logoutAction()
    {
        $this->logout();
    }

    public function rule_protocolAction()
    {

    }

    public function rule_protocol_youmeiAction()
    {

    }

    public function rule_indexAction()
    {

    }

    public function rule_index_youmeiAction()
    {

    }

    public function test_form_uploadAction()
    {

    }

    public function shareAction()
    {

    }

    public function create_share_urlAction()
    {

    }

    public function create_share_url_doAction()
    {

        $uid = $this->request->getPost("uid");
        $url = "http://111.230.127.71/qllivef/web/share/index.html?id=".$uid."&nickname=".$uid;

        $this->view->url = $url;

    }


    public function share_app_downloadAction()
    {

        $mobile_type = $this->get_device_type();

        if ($mobile_type == "ios") {
            $download_url = "https://itunes.apple.com/cn/app/id1263845552";
        } else {
            $download_url = "http://web-1253795974.cosgz.myqcloud.com/apk/quliao.apk";
        }

        $this->view->url = $download_url;
        $this->view->mobile_type = $mobile_type;


    }

    public function share_app_download_youmeiAction()
    {

        $mobile_type = $this->get_device_type();
        if ($mobile_type == "ios") {
            $download_url = "https://www.pgyer.com/umei";
        } else {
            $download_url = "http://web-1253795974.cosgz.myqcloud.com/apk/youmei.apk";
        }


        $this->view->url = $download_url;
        $this->view->mobile_type = $mobile_type;


    }

    public function get_device_type()
    {
        //全部变成小写字母
        $agent = strtolower($_SERVER['HTTP_USER_AGENT']);
        $type = 'other';
        //分别进行判断
        if (strpos($agent, 'iphone') || strpos($agent, 'ipad')) {
            $type = 'ios';
        }

        if (strpos($agent, 'android')) {
            $type = 'android';
        }

        return $type;
    }


    /**
     * the user's protocol
     */
    public function user_protocolAction()
    {

    }

    /**
     * the ym's user's protocol
     */
    public function ym_user_protocolAction()
    {

    }

    public function user_rewardAction()
    {

    }

    public function base_salaryAction()
    {

    }


}
