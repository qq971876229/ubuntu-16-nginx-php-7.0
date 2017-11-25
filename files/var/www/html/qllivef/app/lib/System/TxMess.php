<?php

namespace App\Lib\System;

use App\Lib\Tim\TimRestApi;


use App\Lib\System\App;
use App\Domain\usersDomain;
use App\Models\vodSessionModel;
use App\Models\systemMessModel;


//腾讯消息
class TxMess
{

    private $api_;

    private function get_api()
    {


        if ($this->api_ == null) {
            $this->api_ = TimRestApi::create();
        }


        return $this->api_;
    }

    public function create_tx_user($id, $nickname, $img)
    {

        $birthday = 788889600;
        $this->get_api()->account_import($id, $nickname, $img);

        #构造高级接口所需参数
        $profile_list = array();
        $profile_birthday = array(
            "Tag" => "Tag_Profile_IM_BirthDay",
            "Value" => $birthday,
        );

        array_push($profile_list, $profile_birthday);

        $profile_nick = array(
            "Tag" => "Tag_Profile_IM_Nick",
            "Value" => $nickname,
        );

        array_push($profile_list, $profile_nick);

        $result = $this->get_api()->profile_portrait_set2($id, $profile_list);


    }

    public function edit_tx_user($id, $nickname=0, $img=0, $birthday=0, $sex=0, $signature=0)
    {

//        $img = '';
//        $login = $this->get_api()->account_import($id, $nickname, $img);
//
//        var_dump($login);

        #构造高级接口所需参数
        $profile_list = array();

        if($nickname!==0){

            $profile_nickname = array(
                "Tag" => "Tag_Profile_IM_Nick",
                "Value" => $nickname,
            );

            array_push($profile_list, $profile_nickname);
        }

        if($signature!==0){

            $profile_signature = array(
                "Tag" => "Tag_Profile_IM_SelfSignature",
                "Value" => $signature,
            );

            array_push($profile_list, $profile_signature);
        }

        if($img!==0){
            $profile_img = array(
                "Tag" => "Tag_Profile_IM_Image",
                "Value" => $img,
            );

            array_push($profile_list, $profile_img);
        }

        if($birthday!==0){
            $profile_birthday = array(
                "Tag" => "Tag_Profile_IM_BirthDay",
                "Value" => $birthday,
            );

            array_push($profile_list, $profile_birthday);

        }

        if($sex!==0){
            $profile_sex = array(
                "Tag" => "Tag_Profile_IM_Gender",
                "Value" => $sex,
            );

            array_push($profile_list, $profile_sex);

        }



        $id = "$id";  // let the id string

        $result = $this->get_api()->profile_portrait_set2($id, $profile_list);

//        $res_data = App::TxMess()->get_user_info($id);

//        var_dump($res_data);


        return $result;


    }

    public function user_state($uid)
    {

        $value = \configLive::user_online_state['Offline'];

        $res = $this->get_api()->querystate([$uid]);

        if ($res['ActionStatus'] == "OK") {

            if ($res['QueryResult'][0]['State'] == "Online") {
                $value = \configLive::user_online_state['free'];
            }

        }

        if (vodSessionModel::is_live($uid) == true) {

            $value = \configLive::user_online_state['busy'];
        }

        return $value;

    }

    /**
     * get the live's online state
     * @param $uid
     * @return mixed
     */
    public function user_live_state($uid)
    {

        $value = \configLive::user_online_state['Offline'];

        $url = 'http://fcgi.video.qcloud.com/common_access?';
        $appid = '';
        $interface = 'Live_Channel_GetStatus';

        $res = $this->get_api()->querystate([$uid]);

        if ($res['ActionStatus'] == "OK") {

            if ($res['QueryResult'][0]['State'] == "Online") {
                $value = \configLive::user_online_state['free'];
            }

        }

        if (vodSessionModel::is_live($uid) == true) {

            $value = \configLive::user_online_state['busy'];
        }

        return $value;

    }





    public function check_online($live_id)
    {
        $res = $this->get_api()->querystate([$live_id]);


        if ($res['ActionStatus'] != "OK") {
            App::Input()->error($res['ErrorInfo']);
        }

        if ($res['QueryResult'][0]['State'] != "Online") {

            $http_host = $_SERVER['HTTP_HOST'];

            if ($http_host == 'ymapi.miyintech.com') {

                return 0;

            } else {

                return 0;
            }


        } else {
            return 1;
        }

    }

    public function live_accept($live_id, $uid, $content)
    {
        $info = $this->create_mess(\config::mess_type['live_accept'], $content);

        $res = $this->get_api()->openim_send_custom_msg($live_id, $uid, $info);

        if ($res['ActionStatus'] != "OK") {
            App::Input()->error($res['ErrorInfo']);
        }

    }

    public function live_start($uid, $live_id, $content)
    {

        $info = $this->create_mess(\config::mess_type['live_start'], $content);


        $res = $this->get_api()->openim_send_custom_msg($uid, $live_id, $info);

        if ($res['ActionStatus'] != "OK") {
            App::Input()->error($res['ErrorInfo']);
        }


    }

    public function get_user_info($uid)
    {

        $uid = (string)$uid;

        $data = $this->get_api()->profile_portrait_get($uid);


        $user = new \stdClass();

        $user->id = $uid;
        $user->nickname = "";
        $user->sex = "";
        $user->birthday = 0;
        $user->location = "";
        $user->img = "";
        $user->price = 2;

        $user->signature = "";


        if ($data['ActionStatus'] != "OK") {
            return $user;
        }


        $info = $data['UserProfileItem'];

        if (isset($info[0]['ProfileItem']) == false) {
            return $user;
        }


        foreach ($info[0]['ProfileItem'] as $v) {


            if ($v['Tag'] == "Tag_Profile_IM_Nick") {
                $user->nickname = $v['Value'];
            }

            if ($v['Tag'] == "Tag_Profile_IM_Gender") {
                $user->sex = $v['Value'];
            }

            if ($v['Tag'] == "Tag_Profile_IM_BirthDay") {
                $user->birthday = $v['Value'];
            }

            if ($v['Tag'] == "Tag_Profile_IM_Location") {
                $user->location = $v['Value'];
            }

            if ($v['Tag'] == "Tag_Profile_IM_Image") {
                $user->img = $v['Value'];
            }

            if ($v['Tag'] == "Tag_Profile_IM_SelfSignature") {
                $user->signature = $v['Value'];
            }


        }

        return $user;


    }

    public function send_gift($uid, $live_id, $gift_info)
    {
        $this->send_mess(\config::app['live_admin'], $live_id, \config::mess_type['gift'], $gift_info);
        $this->send_mess(\config::app['live_admin'], $uid, \config::mess_type['gift'], $gift_info);

    }

    /**
     * send system message
     * @param $uid
     * @param $content
     * @return bool
     */
    public function system_mess($uid, $content)
    {
        if ($uid != 0) {

            App::TxMess()->send_mess(\config::app['live_service'], $uid, \config::mess_type['mess'], $content);

        } else {
            App::TxMess()->send_all_mess($content);
        }

        systemMessModel::add($uid, $content);

        return true;

    }


    /**
     *
     * @param $from_uid
     * @param $to_uid
     * @param $content
     * @return bool
     */
    public function user_to_user_message($from_uid, $to_uid, $content)
    {

        App::TxMess()->send_text_mess($from_uid, $to_uid, 4, $content);

        return true;

    }

    public function get_user_attr($uid)
    {
        $attr = $this->get_api()->queryattr([$uid]);

        return $attr;
    }

    public function set_user_attr($uid, $key, $value)
    {
        $attr = $this->get_api()->setattr($uid, $key, $value);

        return $attr;
    }

    public function create_attr_name($attr_names)
    {
        $attr = $this->get_api()->create_attr_name($attr_names);

        return $attr;
    }

    private function send_mess($suid, $duid, $type, $content)
    {
        $info = $this->create_mess($type, $content);


        $res = $this->get_api()->openim_send_custom_msg((string)$suid, (string)$duid, $info);

        if ($res['ActionStatus'] != "OK") {
            App::Input()->error($res['ErrorInfo']);
        }
    }


    private function send_text_mess($suid, $duid, $type, $content)
    {
        $info = $this->create_mess($type, $content);

        $res = $this->get_api()->openim_send_msg_text((string)$suid, (string)$duid, $info);

        if ($res['ActionStatus'] != "OK") {
            App::Input()->error($res['ErrorInfo']);
        }
    }





    private function send_all_mess($content)
    {

        $info = $this->create_mess(\config::mess_type['all_mess'], $content);

        $res = $this->get_api()->im_push($info);


        if ($res['ActionStatus'] != "OK") {
            App::Input()->error($res);
        }


    }

    private function create_mess($type, $content)
    {

        $info = new \stdClass();;

        $info->userAction = $type;

        $info->actionParam = $content;

        return $info;
    }


}