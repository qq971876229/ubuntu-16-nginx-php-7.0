<?php

namespace App\Controllers\mgr;

use App\Lib\System\App;
use App\Lib\System\Input;
use App\Models\userImgModel;
use App\Models\usersMgrModel;
use App\Models\usersModel;
use App\Models\moneyModel;
use App\Models\systemMessModel;
use App\Models\logPayModel;
use App\Models\cashNotesModel;
use App\Domain\rankListDomain;
use App\Models\adModel;
use App\Models\feedbackModel;
use App\Models\reportedModel;
use App\Models\usersStateModel;
use App\Models\vodSessionModel;

class ApiController extends baseController
{

    public function get_user_listAction()
    {
        $this->isAuth();

        $page = App::Input()->get_page();

        $key = App::Input()->get("key");

        $is_live = App::Input()->get("is_live");

        $is_view = App::Input()->get("is_view");

        $online = App::Input()->get("online");

        App::Input()->out(usersModel::get_user_list_pc($page, $key, $is_live, $is_view, $online));
    }


    public function get_live_listAction()
    {
        $this->isAuth();

        $page = App::Input()->get_page();

        App::Input()->out(usersModel::get_live_list_pc($page));
    }


    public function get_share_money_listAction()
    {
        $this->isAuth();

        $page = App::Input()->get_page();

        $uid = App::Input()->get("uid");

        App::Input()->out(moneyModel::get_share_pc($page, $uid));

    }


    public function get_system_mess_listAction()
    {

        $this->isAuth();

        $page = App::Input()->get_page();

        App::Input()->out(systemMessModel::get_list_pc($page));

    }


    public function get_ad_listAction()
    {


        $this->isAuth();

        $page = App::Input()->get_page();

        App::Input()->out(adModel::list_pc($page));

    }


    public function del_adAction()
    {
        $this->isAuth();
        $id = App::Input()->get("id");
        adModel::del($id);
        App::Input()->out("ok");

    }

    public function edit_adAction()
    {
        $this->isAuth();

        $id = App::Input()->get("id");
        $img = App::Input()->get("img");
        $link = App::Input()->get("link");
        $type = App::Input()->get("type");
        $sort = App::Input()->get("sort");


        adModel::edit($id, $img, $link, $type, $sort);

        App::Input()->out("ok");
    }

    public function add_adAction()
    {
        $this->isAuth();

        $img = App::Input()->get("img");
        $link = App::Input()->get("link");
        $type = App::Input()->get("type");
        $sort = App::Input()->get("sort");


        adModel::add_ad($img, $link, $type, $sort);

        App::Input()->out("ok");
    }


    public function set_blackAction()
    {
        $this->isAuth();

        $uid = App::Input()->get("uid");

        usersModel::set_black($uid);

        App::Input()->out("ok");
    }

    // cancel the host
    public function cancel_hostAction()
    {

        $this->isAuth();

        $uid = App::Input()->get("uid");

        usersModel::cancle_host($uid);

        App::Input()->out("ok");
    }

    public function delete_userAction()
    {

        $this->isAuth();

        $uid = App::Input()->get("uid");

        usersModel::delete_user($uid);

        App::Input()->out("ok");
    }

    /**
     * to be goddess,active,or new
     */
    public function set_host_typeAction()
    {
        $this->isAuth();

        $uid = App::Input()->get("uid");
        $host_type = App::Input()->get("host_type");

        usersModel::set_host_type($uid, $host_type);

        App::Input()->out("ok");
    }

    /**
     * stick or not
     */
    public function set_stickAction()
    {
        $this->isAuth();

        $uid = App::Input()->get("uid");

        usersModel::set_stick($uid);

        App::Input()->out("ok");
    }


    public function get_live_app_listAction()
    {
        $this->isAuth();

        $page = App::Input()->get_page();

        App::Input()->out(usersModel::get_apply_list_pc($page));
    }

    public function auth_checkAction()
    {

        $this->isAuth();

        $uid = App::Input()->get("uid");
        $state = App::Input()->get("state");
        $mess = App::Input()->get("mess");

        usersModel::auth_check($uid, $state, $mess);

        App::Input()->out("ok");
    }


    public function recharge_listAction()
    {
        $this->isAuth();
        $page = App::Input()->get_page();

        $page['start_time'] = strtotime($page['start_time']);
        $page['end_time'] = strtotime($page['end_time']);


        App::Input()->out(logPayModel::recharge_list_pc($page));

    }

    public function total_rechargeAction()
    {

        $start_time = $this->request->get("start_time");
        $end_time = $this->request->get("end_time");

        $total_recharge = logPayModel::total_recharge($start_time, $end_time);

        echo $total_recharge;

    }


    public function cash_listAction()
    {
        $this->isAuth();
        $page = App::Input()->get_page();

        $type = App::Input()->get("type");

        App::Input()->out(cashNotesModel::get_cash_list_pc($page, $type));

    }

    public function cash_checkAction()
    {

        $this->isAuth();


        $state = App::Input()->get("state");
        $id = App::Input()->get("id");

        if ($state == 1)//通过
        {
            App::Input()->out(cashNotesModel::check_cash($id, ""));
        } else {
            if ($state == 2)//取消
            {
                App::Input()->out(cashNotesModel::cancel_cash($id, ""));
            }
        }


    }


    public function rank_listAction()
    {
        $this->isAuth();

        $type = App::Input()->get("type");
        $date = App::Input()->get("date");
        $uid = App::Input()->get("uid");
        $path = "ranklist:".$type.":".$uid;


        rankListDomain::add($type, $uid, $date);


        App::Input()->out("ok");
    }

    public function feedbackAction()
    {
        $this->isAuth();
        $page = App::Input()->get_page();

        App::Input()->out(feedbackModel::get_list_web($page));

    }

    public function reportedAction()
    {
        $this->isAuth();
        $page = App::Input()->get_page();

        App::Input()->out(reportedModel::get_list_web($page));

    }

    /**
     * common delete by record id
     */
    public function delete_recordAction()
    {

        $id = $this->request->getPost('id');
        $table = $this->request->getPost('table');

        $sql = "DELETE FROM $table WHERE id=$id";

        $result = usersModel::DB()->getOne($sql);

        echo $result;
    }

    /**
     * common delete by record id
     */
    public function edit_recordAction()
    {
        $id = $this->request->getPost("id");
        $table = $this->request->getPost('table');
        $field = $this->request->getPost('field');

        $sql = "UPDATE $table SET $field  WHERE id=$id";

        $uid = 0;
        if ($table == "users") {
            $uid = $id;
        }

        $nickname = $this->request->getPost("nickname");
        $birthday = $this->request->getPost("birthday");
        $price = $this->request->getPost("price");
        $sex = $this->request->getPost("sex");
        $signature = $this->request->getPost("signature");

        if ($nickname) {
            $tx_user = App::TxMess()->edit_tx_user($uid, $nickname);
            $user = usersModel::info($uid);
            $user->nickname = $nickname;

            usersModel::clear($uid);

            \bcl\redis\base::get_rd()->set("users:base:".$uid, json_encode($user));

        }

        if ($signature) {
            $tx_user = App::TxMess()->edit_tx_user($uid, 0,0,0,0,$signature);
            $user = usersModel::info($uid);
            $user->signature = $signature;

            usersModel::clear($uid);

            \bcl\redis\base::get_rd()->set("users:base:".$uid, json_encode($user));

        }


        if ($birthday) {
            $birthday = intval($birthday);
            $tx_user = App::TxMess()->edit_tx_user($uid, 0, 0, $birthday);
            $user = usersModel::info($uid);
            $user->birthday = $birthday;

            usersModel::clear($uid);
            \bcl\redis\base::get_rd()->set("users:base:".$uid, json_encode($user));

        }

        if ($sex) {
            $tx_user = App::TxMess()->edit_tx_user($uid, 0, 0, 0,$sex);
            $user = usersModel::info($uid);
            $user->sex = $sex;

            usersModel::clear($uid);
            \bcl\redis\base::get_rd()->set("users:base:".$uid, json_encode($user));

        }

        if ($price) {
            $user = usersModel::info($uid);
            $user->price = $price;

            usersModel::clear($uid);
            \bcl\redis\base::get_rd()->set("users:base:".$uid, json_encode($user));

        }

        $result = usersModel::DB()->query_sql($sql);

        echo $result;

    }

    /**
     * delete the portrait
     */
    public function delete_portraitAction()
    {

        $uid = $this->request->getPost("uid");

        $tx_user = App::TxMess()->edit_tx_user($uid, 0, 'no portrait');

        if ($tx_user['ActionStatus'] == 'OK') {
            usersModel::clear($uid);
            echo 'ok';
        } else {
            echo 'fail';
        }
    }

    public function clear_cacheAction()
    {

        $uid = $this->request->get("uid");

        $uid = 245206;

        usersModel::clear($uid);


    }


}
