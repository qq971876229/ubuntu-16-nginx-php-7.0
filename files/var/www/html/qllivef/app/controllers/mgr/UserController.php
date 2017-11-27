<?php

namespace App\Controllers\mgr;

use App\Lib\System\App;
use App\Models\cashNotesModel;
use App\Models\moneyModel;
use App\Models\userImgModel;
use App\Models\usersMgrModel;
use App\Domain\usersDomain;
use App\Models\feedbackModel;
use App\Models\usersModel;
use App\Models\vodSessionModel;
use App\RModels\userTimeCountRModel;

class UserController extends baseController
{

    public function edit_passAction()
    {
        $user_info = $this->isAuth();

        $this->view->mess = "";


        $old_user_pass = $this->request->getPost("old_user_pass");
        $new_user_pass1 = $this->request->getPost("new_user_pass1");
        $new_user_pass2 = $this->request->getPost("new_user_pass2");


        $this->view->mess = "请修改密码";


        if (strlen($old_user_pass) < 1) {
            return;
        }

        if (strlen($new_user_pass1) < 6) {
            $this->view->mess = "密码长度大于6";

            return;
        }

        if ($new_user_pass1 != $new_user_pass2) {

            $this->view->mess = "重复密码不一致";

            return;
        }


        if (usersMgrModel::check_pass($user_info->login_name, $old_user_pass) == false) {
            $this->view->mess = "密码错误";

            return;
        }


        usersMgrModel::edit_pass($user_info->login_name, $new_user_pass2);

        $this->view->mess = "修改成功";


    }

    public function show_videoAction()
    {

        $video_url = $this->request->get("video_url");
        $uid = $this->request->get("uid");
        $item_id = $this->request->get("item_id");

        $this->view->video_url = $video_url;
        $this->view->uid = $uid;
        $this->view->item_id = $item_id;

    }

    public function mgr_user_listAction()
    {
        $user_info = $this->isAuth();

        $list = usersMgrModel::find();

        $this->view->list = $list;

    }

    public function mgr_user_delAction()
    {
        $user_info = $this->isAuth();

        $id = $this->request->get("id");

        $user = usersMgrModel::findFirst("id = '$id'");

        $user->delete();

        $this->response->redirect('/index.php?_url=/mgr/user/mgr_user_list');

    }

    /**
     * show the user's detail
     */
    public function user_detailAction()
    {

        $uid = $this->request->get("id");

        $res_data = App::TxMess()->get_user_info($uid);

        $signature = $res_data->signature;

        $user = usersmodel::info($uid);


        $user->img = \config::app['img_bucket_url'].$user->img;


        $user_info = usersModel::findFirst("id = '$uid'");

        if ($user->auth_state == 1 || $user->auth_state == 2) {
            $user->auth = '主播';
            $host = 1;
        } else {
            $user->auth = '观众';
            $host = 0;
        }

        $total_recharge = moneyModel::get_total_recharge($uid);

        $total_withdrawal = cashNotesModel::get_total_withdrawal($uid);

        $balance = moneyModel::get_balance($uid);

        $recommend_money = moneyModel::get_recommend_money($uid);

        $talk_money = moneyModel::get_talk_money($uid, $host);

        $gift_money = moneyModel::get_gift_money($uid, $host);

        $recommend_num = usersModel::get_recommend_num($uid);

        $talk_long = vodSessionModel::get_talk_long($uid, $host);

        $talk_times = vodSessionModel::get_talk_times($uid, $host);

        $success_talk_times = vodSessionModel::get_talk_times_success($uid, $host);

        $fail_talk_times = vodSessionModel::get_talk_times_fail($uid, $host);

        $talk_status = vodSessionModel::get_talk_status($uid, $host);


        if ($fail_talk_times + $success_talk_times == 0) {

            $answer_rate = 0;
        } else {

            $answer_rate = substr($success_talk_times / ($fail_talk_times + $success_talk_times) * 100, 0, 5);
        }
        $answer_rate .= "%";

        $this->view->uid = $uid;

        $online_time_long = userTimeCountRModel::get_online_time_long($uid);



        $this->view->signature = $signature;
        $this->view->user = $user;
        $this->view->user_info = $user_info;
        $this->view->total_recharge = $total_recharge;
        $this->view->balance = $balance;
        $this->view->recommend_money = $recommend_money;
        $this->view->talk_money = $talk_money;
        $this->view->recommend_num = $recommend_num;
        $this->view->talk_long = $talk_long;
        $this->view->talk_times = $talk_times;
        $this->view->success_talk_times = $success_talk_times;
        $this->view->fail_talk_times = $fail_talk_times;
        $this->view->talk_status = $talk_status;
        $this->view->gift_money = $gift_money;
        $this->view->online_time_long = $online_time_long;
        $this->view->total_withdrawal = $total_withdrawal;
        $this->view->answer_rate = $answer_rate;

    }

    /**
     * show the user's photo list
     */
    public function photo_listAction()
    {

        $uid = $this->request->get("uid");

        $photo_list = userImgModel::get_list($uid);

        $cloud_url = \config::app['img_bucket_url'];


        foreach ($photo_list as $k => $v) {
            $photo_list[$k]['img'] = $cloud_url.$v['img'];
            if (!isset($v['video'])) {
                $photo_list[$k]['video'] = 0;
            } else {

                $photo_list[$k]['video'] = \config::app['video_bucket_url'].$v['video'];
            }
        }


        $this->view->list = $photo_list;


    }


    /**
     * modify the user's reference id
     */
    public function edit_reference_idAction()
    {

        $uid = $this->request->get("uid");
        $reference = $this->request->get("reference");

        $user = usersModel::findFirst($uid);

        $user->reference = $reference;
        $user->reference_time = time() + 100000000;

        $result = $user->save();

        if ($result) {
            echo 1;
        } else {
            echo 0;
        }

    }


    public function recommend_listAction()
    {

        $uid = $this->request->get("id");
        $recommend_list = usersModel::get_recommend_list($uid);

        $this->view->list = $recommend_list;

    }


    public function mgr_user_editAction()
    {

        $this->isAuth();


        $id = $this->request->get("id");

        $user = usersMgrModel::findFirst("id = '$id'");

        $this->view->user_name = $user->login_name;
        $this->view->mess = "";

        $this->view->id = $id;

        $new_user_pass1 = $this->request->getPost("new_user_pass1");
        $new_user_pass2 = $this->request->getPost("new_user_pass2");

        if (strlen($new_user_pass2) < 1) {
            return;
        }

        if ($new_user_pass1 != $new_user_pass2) {

            $this->view->mess = "重复密码不一致";

            return;
        }

        usersMgrModel::edit_pass($user->login_name, $new_user_pass1);

        $this->response->redirect('/index.php?_url=/mgr/user/mgr_user_list');

    }


    public function mgr_user_addAction()
    {

        $user_info = $this->isAuth();

        $this->view->mess = "添加用户";


        $user_name = $this->request->getPost("user_name");
        $new_user_pass1 = $this->request->getPost("new_user_pass1");
        $new_user_pass2 = $this->request->getPost("new_user_pass2");

        if (strlen($user_name) < 1) {
            return;
        }

        if (strlen($new_user_pass1) < 6) {
            $this->view->mess = "密码长度大于6";

            return;
        }

        if ($new_user_pass1 != $new_user_pass2) {

            $this->view->mess = "重复密码不一致";

            return;
        }

        usersMgrModel::add_user($user_name, $new_user_pass1);
        $this->view->mess = "添加成功";

    }

    public function user_listAction()
    {

        $this->isAuth();
    }

    public function live_listAction()
    {
        $this->isAuth();

        $list = usersMgrModel::find();

        var_dump($list->toArray());

        $this->view->list = $list;
    }

    public function live_applyAction()
    {
        $this->isAuth();

    }


    public function check_liveAction()
    {
        $this->isAuth();

        $uid = $this->request->get("uid");

        $user = new usersDomain($uid);

        $u = $user->full_info();

        $u->vod = \config::app['file_bucket_url'].$u->vod;
        $u->img = \config::app['img_bucket_url'].$u->img;

        $this->view->user = $u;
    }

    public function check_endAction()
    {
        ;
    }


    public function rank_listAction()
    {
        $this->isAuth();

        $uid = $this->request->get("uid");

        $this->view->uid = $uid;
    }

    //投诉
    public function feedbackAction()
    {
        $this->isAuth();


    }

    //举报
    public function reportedAction()
    {
        $this->isAuth();

    }




}
