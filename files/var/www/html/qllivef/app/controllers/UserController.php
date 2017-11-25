<?php

namespace App\Controllers;

use App\Models\logLoginModel;
use App\Models\systemMessModel;
use App\Models\userImgModel;
use App\Lib\System\App;
use App\Models\feedbackModel;
use App\Models\usersModel;
use App\Domain\usersDomain;
use App\Models\rankUserModel;
use App\Models\reportedModel;
use App\Lib\auth_login;
use App\Models\logGiftModel;
use App\Models\userVideoModel;
use App\Models\vodSessionModel;


class UserController extends baseController
{


    public function fillAction()
    {
        $uid = App::Auth()->authLogin();

        $reference_id = App::Input()->get("reference_id");

        if ($uid == $reference_id) {
            App::Input()->out("不能填写自己的uid");
            exit;
        }

        $nickname = App::Input()->get("nickname");

        if (!$reference_id) {
            $reference_id = 0;
        }

        usersModel::fill($uid, $reference_id, $nickname);
        App::Input()->out("ok");
    }


    public function photo_addAction()
    {
        $uid = App::Auth()->authLogin();

        $img = App::Input()->get("img");

        userImgModel::add($uid, $img);

        App::Input()->out("ok");
    }

    public function photo_delAction()
    {
        $uid = App::Auth()->authLogin();

        $id = App::Input()->get("id");

        userImgModel::del($uid, $id);

        App::Input()->out("ok");

    }

    public function photo_listAction()
    {
        App::Auth()->authLogin();

        $uid = App::Input()->get("uid");
        App::Input()->out(userImgModel::get_list($uid));
    }

    /**
     * get the person show video
     */
    public function video_listAction()
    {

        App::Auth()->authLogin();

        $uid = App::Input()->get("uid");

        App::Input()->out(userVideoModel::get_list($uid));
    }

    /**
     * stick the person show video to be first show
     */
    public function stick_videoAction()
    {

        $uid = App::Auth()->authLogin();
        $vid = App::Input()->get("video_id");

        App::Input()->out(userVideoModel::stick($uid, $vid));

    }


    /**
     * delete the person video
     */
    public function delete_videoAction()
    {

        $uid = App::Auth()->authLogin();

        $vid = App::Input()->get("video_id");

        userVideoModel::del($uid, $vid);

        App::Input()->out("ok");

    }


    //留言
    public function feedbackAction()
    {
        $uid = App::Auth()->authLogin();

        $content = App::Input()->get("content");

        feedbackModel::add($uid, $content);

        App::Input()->out("ok");
    }


    /**
     * if person_show is empty update the certification photo and certification video  to the user table
     * if person_show is 1 ,add the person show video
     */
    public function authAction()
    {
        $uid = App::Auth()->authLogin();

        $person_show = App::Input()->get("person_show");

        if ($person_show == 1) { // add the person show video

            $img = App::Input()->get("img");
            $vod = App::Input()->get("vod");
            userVideoModel::add($uid, $vod, $img);

        } else {
            $img = App::Input()->get("img");
            $vod = App::Input()->get("vod");
            usersModel::auth($uid, $img, $vod);
        }


        App::Input()->out("ok");

    }


    public function infoAction()
    {
        App::Auth()->authLogin();

        $uid = App::Input()->get("uid");

        usersModel::sync_balance($uid);

        $user = new usersDomain($uid);

        $info = $user->full_info();

        $balance = usersModel::get_balance($uid);
        $info->money = $balance;


        if ($info == false) {
            App::Input()->error("用户不存在");
        }


        // get the user's phone type
        $my_user = usersModel::findFirst("id=".$uid);

        if (empty($my_user->phone_type)) {  // just get once

            if (strpos($_SERVER['HTTP_USER_AGENT'], 'iPhone') || strpos($_SERVER['HTTP_USER_AGENT'], 'iPad')) {

                $phone_type = "ios";
            } else {

                if (strpos($_SERVER['HTTP_USER_AGENT'], 'Android')) {

                    $phone_type = "android";
                } else {

                    $phone_type = "unknow";
                }
            }

            $my_user->phone_system = $_SERVER['HTTP_USER_AGENT'];
            $my_user->phone_type = $phone_type;
            $my_user->save();
        }

        // login log
        logLoginModel::add($uid);

        //simulate live send message to user
//        if($my_user->auth_state!=2){
//
//            systemMessModel::rand_live_to_user($uid);
//        }



        App::Input()->out($info);


    }

    public function edit_infoAction()
    {
        $uid = App::Auth()->authLogin();

        $info = App::Input()->get_array_null(array("img", "nickname", "remark", "sex", "home", "constellation", "age"));


        if (isset($info['nickname'])) {

            if (strpos($info['nickname'], " ")) {
                App::Input()->error("昵称不能有空格");
            }
        }


        usersModel::edit($uid, $info);

        $user = new usersDomain($uid);

        App::Input()->out($user->full_info());
    }


    public function rankAction()
    {
        App::Input()->out(rankUserModel::get_rank());
    }

    public function numberAction()
    {
        $type = App::Input()->get("type");

        App::Input()->out(logGiftModel::get_number_new($type));
//        App::Input()->out(logGiftModel::get_number($type));
    }


    public function clear_cacheAction()
    {
        $uid = App::Auth()->authLogin();

        usersModel::clear($uid);

        App::Input()->out("ok");

    }

    public function send_login_smsAction()
    {

        $moblie = App::Input()->get("moblie");

        App::Moblie()->send_login_code($moblie);
        App::Input()->out("ok");
    }

    public function send_login_sms2Action()
    {

//        $moblie = App::Input()->get("moblie");


        $moblie = $this->request->get("moblie");


        App::Moblie()->send_login_code2($moblie);

        echo $_GET['callback']."(".json_encode('发送成功').")";
        exit;

    }


    public function login_smsAction()
    {
        $moblie = App::Input()->get("moblie");
        $code = App::Input()->get("code");

        $reference = App::Input()->get_null("reference", 0);


        $is_apple = 0;


        if ($moblie == "15605886005" || $moblie == "18055427302" || $moblie == "13811111111" || $moblie == "13822222222" || $moblie == "18780060693" || $moblie == "18571718480") {
            $is_apple = 1;
        }



        if (App::Moblie()->check_login_code($moblie, $code) || $is_apple == 1) {

            $uid = usersModel::login_moblie2uid($moblie);

            if ($uid == false) {
                $http_host = $_SERVER['HTTP_HOST'];

//                $uid = usersModel::create_login_moblie_user($moblie,$reference);
                if ($http_host == 'testapi.chumao.net') {
                    App::Input()->error("测试服务器，请重新下载正式服应用");exit;
//                    $uid = usersModel::create_login_moblie_user($moblie, $reference);
                } else {
                    $uid = usersModel::create_login_moblie_user($moblie, $reference);
                }


            } else {
                if ($reference != 0) {
                    App::Input()->error("账号已经注册");
                }
            }


            if ($uid == false) {
                App::Input()->error("创建账号错误");
            }


            vodSessionModel::free_session($uid);

            App::Input()->out(App::Auth()->auth($uid));


        } else {
            App::Input()->error("验证码错误");
        }
    }

    public function login_sms_shareAction()
    {
//        $moblie = App::Input()->get("moblie");
//        $code = App::Input()->get("code");
//
//        $reference = App::Input()->get_null("reference", 0);

        $moblie = $this->request->get("moblie");
        $code = $this->request->get("code");
        $reference = $this->request->get("reference");

        if (!$reference) {
            $reference = 0;
        }

        $is_apple = 0;

        if ($moblie == "15605886005" || $moblie == "18055427302") {
            $is_apple = 1;
        }

        if (App::Moblie()->check_login_code($moblie, $code) || $is_apple == 1) {
            $uid = usersModel::login_moblie2uid($moblie);

            if ($uid == false) {
                $http_host = $_SERVER['HTTP_HOST'];

//                $uid = usersModel::create_login_moblie_user($moblie,$reference);
                if ($http_host == 'testapi.miyintech.com') {
                    App::Input()->error("测试服务器，请重新下载正式服应用");
                } else {
                    $uid = usersModel::create_login_moblie_user($moblie, $reference);
                }


            } else {
                if ($reference != 0) {
                    App::Input()->error("账号已经注册");
                }
            }


            if ($uid == false) {
                App::Input()->error("创建账号错误");
            }


            vodSessionModel::free_session($uid);


            echo $_GET['callback']."(".json_encode($uid).")";
            exit;


//            App::Input()->out(App::Auth()->auth($uid));

        } else {

            echo $_GET['callback']."(".json_encode(0).")";
            exit;

//            App::Input()->error("验证码错误");
        }
    }

    public function login_sms_cumaoAction()
    {

        $moblie = $this->request->get("moblie");
        $code = $this->request->get("code");
        $reference = $this->request->get("reference");

        if (!$reference) {
            $reference = 0;
        }

        if (App::Moblie()->check_login_code($moblie, $code)) {
            $uid = usersModel::login_moblie2uid($moblie);

            if ($uid == false) {
                $http_host = $_SERVER['HTTP_HOST'];

                if ($http_host == 'testapi.miyintech.com') {
                    App::Input()->error("测试服务器，请重新下载正式服应用");
                } else {
                    $uid = usersModel::create_login_moblie_user($moblie, $reference);
                }

            } else {
                if ($reference != 0) {
                    echo $_GET['callback']."(".json_encode('账号已经注册').")";
                    exit;

                }
            }


            if ($uid == false) {

                echo $_GET['callback']."(".json_encode('创建账号错误').")";
                exit;

            }

            echo $_GET['callback']."(".json_encode('验证码正确').")";
            exit;

        } else {

            echo $_GET['callback']."(".json_encode('验证码错误').")";
            exit;

        }
    }


    public function priceAction()
    {
        $uid = App::Auth()->authLogin();

        $price = App::Input()->get("price");

        if ($price > 5) {
            App::Input()->error("价格不能大于5");
        }


        if ($price < 0) {
            App::Input()->error("价格不能小于0");
        }

        usersModel::edit_price($uid, $price);

        App::Input()->out("ok");;
    }


    public function reportedAction()
    {
        $uid = App::Auth()->authLogin();

        $duid = App::Input()->get("uid");
        $content = App::Input()->get("content");

        reportedModel::add($uid, $duid, $content);

        App::Input()->out("ok");;
    }

    public function login_wxAction()
    {
        $access_token = App::Input()->get("access_token");
        $openid = App::Input()->get("openid");
        $reference_id = App::Input()->get("reference_id");

        $auth_login = new auth_login();

        App::Input()->out($auth_login->login_wx($access_token, $openid, $reference_id));
    }

    public function login_qqAction()
    {

        $access_token = App::Input()->get("access_token");
        $openid = App::Input()->get("openid");

        $auth_login = new auth_login();

        App::Input()->out($auth_login->login_qq($access_token, $openid));
    }


    public function get_share_infoAction()
    {
        $uid = App::Input()->get("uid");

        $user = new usersDomain($uid);

        $nickname = $user->full_info()->nickname;

        $data = new \stdClass();

        $data->nickname = $nickname;
        $data->app_name_cn = \config::app['app_name_cn'];
        $data->app_download_url = \config::app['app_download_url'];
        $data->app_download_url_ios = \config::app['app_download_url_ios'];

        App::Input()->out($data);
    }

    /**
     * get the share user info
     */
    public function get_share_info2Action()
    {

        $uid = $this->request->get("uid");

        $user = new usersDomain($uid);

        $nickname = $user->full_info()->nickname;

//        $data = new \stdClass();

        $data['nickname'] = $nickname;
        $data['app_name_cn'] = \config::app['app_name_cn'];
        $data['app_download_url'] = \config::app['app_download_url'];
        $data['app_download_url_ios'] = \config::app['app_download_url_ios'];


        echo $_GET['callback']."(".json_encode($data).")";
        exit;

    }

    public function get_referenceAction()
    {
        $uid = App::Auth()->authLogin();

        $data = usersModel::get_reference($uid);

        App::Input()->out($data);
    }


    public function disturbAction()
    {
        $uid = App::Auth()->authLogin();

        $value = App::Input()->get("value");

        usersModel::edit_disturb($uid, $value);

        App::Input()->out("ok");

    }

    /**
     * update the request time
     */
    public function update_request_timeAction()
    {

        $uid = App::Auth()->authLogin();

        $update_request_time = usersModel::findFirst($uid);
        $update_request_time->request_time = time();
        $update_request_time->save();
    }


    /**
     * live request this api
     * get the recent vod record
     * return the view user info
     */
    public function get_recent_vodAction()
    {

        $uid = App::Auth()->authLogin();

//        $uid = 231752;

        $vod = vodSessionModel::findFirst(
            array(
                'conditions' => 'live_id=:uid:',
                "bind" => array('uid' => $uid),
                "order" => "id desc",
            )
        );

        $view_info = usersModel::info($vod->view_id);


        $data = new \stdClass();

        $data->id = $vod->id;
        $data->uid = $vod->view_id;
        $data->nickname = $view_info->nickname;
        $data->img = $view_info->img;
        $data->name = '发起聊天请求';
        $data->live_id = $vod->live_id;
        $data->view_id = $vod->view_id;
        $data->live_ttl = $vod->live_ttl;
        $data->view_ttl = $vod->view_ttl;
        $data->begin_time = $vod->begin_time;
        $data->end_time = $vod->end_time;
        $data->view_url = $vod->view_url;
        $data->live_url = $vod->live_url;
        $data->state = $vod->state;
        $data->create_time = $vod->create_time;


        App::Input()->out($data);

    }


    public function my_invitation_listAction()
    {

//        $uid = 231752;

        $uid = App::Auth()->authLogin();
        $data = usersModel::get_recommend_list($uid);

        App::Input()->out($data);

    }


}

