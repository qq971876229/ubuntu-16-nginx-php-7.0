<?php

namespace App\Controllers\mgr;

use App\Lib\System\App;
use App\Models\usersMgrModel;
use App\Models\systemMessModel;
use App\Models\adModel;
use App\Models\usersModel;

class MessController extends baseController
{


    public function sendAction()
    {
        $this->isAuth();
        $this->view->mess = "发送消息";

        $content = $this->request->getPost("content");

        if (strlen($content) < 2) {
            return;
        }

        //send to single user,if the uid is not empty
        $uid = $this->request->getPost("uid");

        // if the uid is empty ,send to selected send object;
        if (!$uid) {


            //send object
            $send_object = $this->request->getPost("send_object");





            if ($send_object == "all_hosts") {


                $all_hosts = usersModel::DB()->getAll('SELECT * FROM users WHERE auth_state=2');
                foreach ($all_hosts as $k => $v) {

                    if (!App::TxMess()->system_mess($v->id, $content)) {
                        echo $v->id."<br/>";
                    }

                }

                $this->response->redirect("./index.php?_url=/mgr/mess/send");
                $this->view->mess = "发送成功";

            } elseif ($send_object == "all_views") {

                $all_views = usersModel::DB()->getAll('SELECT * FROM users WHERE auth_state=1');
                foreach ($all_views as $k => $v) {

                    App::TxMess()->system_mess($v->id, $content);

                }

                $this->response->redirect("./index.php?_url=/mgr/mess/send");
                $this->view->mess = "发送成功";

            } else {


                $uid = 0;

                if (App::TxMess()->system_mess($uid, $content)) {
                    $this->view->mess = "发送成功";
                } else {
                    $this->view->mess = "发送错误";
                }
            }

        } else {

            if (App::TxMess()->system_mess($uid, $content)) {
                $this->view->mess = "发送成功";
            } else {
                $this->view->mess = "发送错误";
            }
        }


    }

    public function listAction()
    {

    }

    public function ad_listAction()
    {

    }


    public function edit_adAction()
    {

        $this->isAuth();
        $id = $this->request->get("id");

        $ad = adModel::get_id($id);

        $this->view->ad = $ad;

        $this->view->url = \config::app['img_bucket_url'];


    }

    public function add_adAction()
    {
        $this->view->url = \config::app['img_bucket_url'];
    }

    public function send_offline_videoAction()
    {

        for($i=0;$i<10;$i++){
            App::TxMess()->system_mess('231752', '6666');
            sleep(1);
        }

    }

    public function get_user_attrAction(){

        $attr =App::TxMess()->get_user_attr("231752");

        echo "<pre>";
        print_r($attr);

    }

    public function set_user_attrAction(){
        $attr =App::TxMess()->set_user_attr("231752","sex",1);

        echo "<pre>";
        print_r($attr);
    }

    public function create_attr_nameAction(){


        $attr =App::TxMess()->create_attr_name("231752");

        echo "<pre>";
        print_r($attr);
    }


}
