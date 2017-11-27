<?php

namespace App\Controllers;

use App\Lib\System\App;
use App\Models\vodSessionModel;
use App\RModels\logRModel;
use App\Lib\qcloud\QcloudApi;


class VodController extends baseController
{

    public function startAction()
    {

        $uid = App::Auth()->authLogin();

        $view_id = App::Auth()->authLogin();

        $live_id = App::Input()->get("live_id");

        $is_debug = App::Input()->get_null("is_debug", 0);

        $is_debug_time = App::Input()->get_null("is_debug_time", "");

//        $mode = vodSessionModel::start($view_id, $live_id, $is_debug, $is_debug_time);
        $mode = vodSessionModel::start_redis($view_id, $live_id, $is_debug, $is_debug_time);

        $return = array(
            "id" => $mode->id,
            "uid" => $view_id,
            "view_id" => $view_id,
            "live_id" => $live_id,
            'view_url' => $mode->view_url,
            'live_url' => $mode->live_url,
            'is_offline' => $mode->is_offline,
            'name' => '发起聊天请求',
            'nickname'=>$mode->nickname,
            'img'=>$mode->img
        );


        App::Input()->out($return);

    }

    public function acceptAction()
    {
        $uid = App::Auth()->authLogin();
        $session_id = App::Input()->get("session_id");

//        $mode = vodSessionModel::accept($session_id, $uid);
        $mode = vodSessionModel::accept_redis($session_id, $uid);

        App::Input()->out(array("id" => $mode->id, 'view_url' => $mode->view_url, 'live_url' => $mode->live_url));
    }


    public function heartbeat_liveAction()
    {
        $uid = App::Auth()->authLogin();

        $session_id = App::Input()->get("session_id");

//        $mode = vodSessionModel::heartbeat_live($session_id, $uid);
        $mode = vodSessionModel::heartbeat_live_redis($session_id, $uid);

        App::Input()->out($mode);


    }

    public function heartbeat_viewAction()
    {
        $uid = App::Auth()->authLogin();

        $session_id = App::Input()->get("session_id");

//        $mode = vodSessionModel::heartbeat_view($session_id, $uid);
        $mode = vodSessionModel::heartbeat_view_redis($session_id, $uid);

        App::Input()->out($mode);

    }


    /**
     * end the vod
     */
    public function endAction()
    {
        $uid = App::Auth()->authLogin();

        $session_id = App::Input()->get("session_id");

        $mode = vodSessionModel::end($session_id);

        App::Input()->out($mode);
    }

    /**
     * end the vod state
     */
    public function end_apiAction(){

        $uid = $this->request->get("uid");
        vodSessionModel::free_session($uid);

    }


    /**
     * vod list to show in phone
     */
    public function listAction()
    {

        $uid = App::Auth()->authLogin();
        $page = App::Input()->get("page");

        App::Input()->out(vodSessionModel::session_list($uid, $page));
    }


    /**
     * no use
     */
    public function checkAction()
    {

        logRModel::log("http:vod", "check");
        vodSessionModel::check_session();

        App::Input()->out("ok");
    }


}

