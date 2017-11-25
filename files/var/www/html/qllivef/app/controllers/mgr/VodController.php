<?php

namespace App\Controllers\mgr;

use App\Lib\System\App;
use App\Models\moneyModel;
use App\Models\vodSessionModel;
use App\RModels\logRModel;
use App\Lib\qcloud\QcloudApi;


class VodController extends baseController
{



    /**
     * vod list to show in phone
     */
    public function indexAction()
    {

        $view_id = $this->request->get('view_id');
        $live_id = $this->request->get('live_id');

        $sql = "SELECT a.id,a.live_id,a.live_ttl,a.view_ttl,a.view_id,a.begin_time,a.end_time,a.money,a.gift_money,a.state FROM vod_session a WHERE 1=1";

        if($view_id){

            $sql .=  " AND view_id='$view_id'";
        }

        if($live_id){

            $sql .= " AND live_id='$live_id' ";
        }

        $sql .= "  ORDER BY create_time desc LIMIT 100";

        $list = vodSessionModel::DB()->getAll($sql);

        foreach($list as $k=>$v){

            // the real fee
            $money = moneyModel::findFirst(array(
                'conditions' => "session_id=".$v->id." AND type=20"
            ));

            $list[$k]->value = $money->value;

            // the newest recharge time
            $is_charge = moneyModel::findFirst(array(
                'conditions' => "uid=".$v->view_id." AND add_time>".$v->begin_time." AND add_time<".$v->end_time." AND type=1",
            ));


            if($is_charge){

                $list[$k]->is_recharge = '充值';
            }else{

                $list[$k]->is_recharge = '未充值';
            }

        }


        $this->view->list = $list;


    }







}

