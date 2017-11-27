<?php

namespace App\Controllers;

use App\Models\usersModel;
use App\Lib\System\App;
use App\Domain\usersDomain;
use App\Domain\rankListDomain;
use App\Models\adModel;


class IndexController extends baseController
{

    /**
     * return the api to phone by type of hot,goddess,new
     */
    public function homeAction()
    {


//        $uid = App::Auth()->is_login();
//
//        if($uid!=0){
//
//            $update_request_time = usersModel::findFirst($uid);
//            $update_request_time->request_time = time();
//            $update_request_time->save();
//        }





        

        $data = new \stdClass();

        $list = array();

        $type = App::Input()->get("type");
        $page = App::Input()->get("page");

        $sex = App::Input()->get_null("sex", 0);

        if ($type == "new") {

            if ($page) {

                $list = rankListDomain::get_by_host_type("new", $page);

            } else {  // old app

                $list = rankListDomain::get_new();
            }

        } else {
            if ($type == "goddess") {
                if ($page) {

                    $list = rankListDomain::get_by_host_type("goddess", $page);


                } else {

                    $list = rankListDomain::get_goddess();
                }

            } else {
                if ($type == "hot") {
                    if ($page) {
                        $list = rankListDomain::get_by_host_type("hot", $page);


                    }else{

                        $list = rankListDomain::get_hot();
                    }
                } else {

                    if($type == 'hot_door'){

                        $list = rankListDomain::get_by_host_type("hot_door", $page);

                    }else{

                        if ($type == "appstore") {
                            $list = rankListDomain::get_appstore();

                        } else {
                            $list = usersDomain::random(100);
                        }
                    }

                }
            }
        }


        $res_list = [];

        foreach ($list as $v) {
            if ($sex == 0) {
                $res_list [] = $v;
            } else {
                if ($sex == 1) {
                    if ($v->sex == "Gender_Type_Male") {
                        $res_list [] = $v;
                    }

                } else {
                    if ($sex == 2) {
                        if ($v->sex == "Gender_Type_Female" || $v->sex == "") {
                            $res_list [] = $v;
                        }
                    }
                }
            }

        }


        $data->ad = adModel::get($type);
        $data->list = $res_list;

        App::Input()->out($data);

    }


    function findAction()
    {
        $key = App::Input()->get("key");


        $page = App::Input()->get_null("page", null);

        if ($page == null) {
            $page = [];

            $page['number'] = 1;
            $page['size'] = 10;
        }


        App::Input()->out(usersModel::find_user($key, $page));
    }

    function recommendAction()
    {

        $data = new \stdClass();

        $data->new = $list = usersDomain::random(10);
        $data->like = $list = usersDomain::random(10);
        App::Input()->out($data);

    }

}

