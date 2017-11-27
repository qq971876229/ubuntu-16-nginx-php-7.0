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

class CacheController extends baseController
{



    public function cache_userAction(){

        $uid = $this->request->get('uid');

        $user = \bcl\redis\base::get_rd()->get("users:base:".$uid);

        $key = "users:base:".$uid;

        $user = array(
            'id'=>$uid,
            'nickname'=>'6',
            'sex'=>'Gender_Type_Female',
            'birthday'=>'691632000',
            'location'=>'北京市',
            'img'=>'no portrait',
            'price'=>'2.00',
            'signature'=>'蓦然回首，众里寻她趣聊吧',
            'auth_state'=>'1',
            'is_black'=>'0',
            'is_fill'=>'1',
            'is_disturb'=>'0',
            'vod'=>'',
            'is_live'=>0,
        );


        $result = \bcl\redis\base::get_rd()->set("users:base:".$uid,json_encode($user));

        var_dump($result);


    }


}