<?php

/**
 * Created by PhpStorm.
 * User: wxx
 * Date: 2017/9/9
 * Time: 上午11:27
 */

namespace App\Controllers\mgr;

use App\Models\settingModel;
use App\Models\usersModel;
use bcl\redis\cacheBase;

class settingController extends baseController
{

    /**
     * setting list
     */
    public function indexAction()
    {
        $user_info = $this->isAuth();

        $http_host = $_SERVER['HTTP_HOST'];

        if ($http_host == 'qlapi.miyintech.com' && $user_info->login_name != 'jobs' &&  $user_info->login_name != 'wxx' ) {

            $list = settingModel::find(array(   // don't show app setting
                "conditions" => "remark!='app'",
                "order" => " order_index desc"
            ));
        }else{

            $list = settingModel::find(array(
                "order" => " order_index desc"
            ));

        }



        $this->view->list = $list;
    }

    /**
     * edit the setting
     */
    public function editAction()
    {

        $id = $this->request->get("id");

        $setting = settingModel::findFirst("id =".$id);

        $options = explode('|', $setting->options);
        $options_name = explode('|', $setting->options_name);

        $this->view->start_time = date('Y-m-d H:i:s',$setting->start_time);
        $this->view->end_time = date('Y-m-d H:i:s',$setting->end_time);
        $this->view->options_name = $options_name;
        $this->view->options = $options;
        $this->view->setting = $setting;
    }


    /**
     * save the setting
     */
    public function do_saveAction()
    {
        $id = $this->request->get("id");
        $setting = settingModel::findFirst($id);

        $value = explode('|', $this->request->get("value"));

        if($this->request->get('start_time')>0){
            $setting->start_time = strtotime($this->request->get("start_time"));
        }

        if($this->request->get('end_time')>0){
            $setting->end_time = strtotime($this->request->get("end_time"));
        }

        $setting->name = $this->request->get("name");
        $setting->value = $value[0];
        $setting->selected_name = $value[1];
        $setting->remark = $this->request->get("remark");
        $setting->input_value = $this->request->get("input_value");
        $setting->order_type = $this->request->get("order_type");

        $setting->save();


        // update the cache
        $setting = settingModel::findFirst("id=$id");

        if($setting) {
            $key = "setting_".$id;
            cacheBase::redis_set($key, $setting);
            cacheBase::redis_set("setting_configflag", time());
        }


        $this->response->redirect('/index.php?_url=/mgr/setting/index');

    }


}