<?php

namespace App\Controllers\mgr;

use App\Lib\System\App;
use App\Models\moneyModel;
use App\Models\vodSessionModel;
use App\RModels\logRModel;
use App\Lib\qcloud\QcloudApi;


class MoneyController extends baseController
{



    /**
     * vod list to show in phone
     */
    public function indexAction()
    {


        $view_id = $this->request->get('view_id');
        $live_id = $this->request->get('live_id');

        $sql = "SELECT * FROM vod_session WHERE 1=1";

        if($view_id){

            $sql .=  " AND view_id='$view_id'";
        }

        if($live_id){

            $sql .= " AND live_id='$live_id' ";
        }

        $sql .= " ORDER BY create_time desc LIMIT 100";

        $list = vodSessionModel::DB()->getAll($sql);

        $this->view->list = $list;


    }


    /**
     * the vod money detail
     */
    public function vodAction(){

        $session_id = $this->request->get('session_id');

        $list = moneyModel::find(array(
            'conditions' => "session_id=$session_id"
        ));

        $this->view->list = $list;

    }

    





}

