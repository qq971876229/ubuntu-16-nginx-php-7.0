<?php

/**
 * Created by PhpStorm.
 * User: wxx
 * Date: 2017/9/9
 * Time: 上午11:27
 */

namespace App\Controllers\mgr;

use App\Models\settingModel;
use App\Models\smsModel;
use App\Models\usersModel;

class smsController extends baseController
{

    /**
     * sms list
     */
    public function indexAction()
    {
        $user_info = $this->isAuth();

        $list = smsModel::find();

        $this->view->list = $list;
    }





}