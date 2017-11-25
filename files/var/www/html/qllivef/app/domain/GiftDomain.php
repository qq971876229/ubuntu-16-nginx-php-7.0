<?php

namespace App\Domain;

use App\Models\moneyModel;
use App\Models\uubeanModel;
use App\Models\logGiftModel;
use App\Lib\System\App;
use App\Lib\System\Transaction;
use App\Models\giftModel;
use App\Models\xpLiveModel;
use App\Models\xpUserModel;
use App\Models\vodSessionModel;







class GiftDomain  extends baseDomain
{
    
    public function __construct($uid)
    {
        $this->setkey($uid);
    }
    
    
    
    public static function occupied_money($session)
    {
                
        $session_value =  vodSessionModel::get_live_money($session);
        
        return $session_value+$session->price;
    }
    
    
    public function send_gift($live_id,$gift_id,$session_id)
    {
        
        $session = vodSessionModel::get($session_id);
        
        if($session == false)
        {
            App::Input()->error("会话不存在");
        }
        
      
        $gift = giftModel::get($gift_id);
        
        if($gift == false)
        {
            App::Input()->error("礼物不存在");
        }
        
        $total_money = $gift->value;
        
        $user_view = new usersDomain($this->getKey());

        $occupied_money = self::occupied_money($session);
      
        
        if( $user_view->full_info()->money < $total_money+$occupied_money)
        {
            App::Input()->error("余额不足");
        }
        

        
        $uid = $this->getKey();
         
        $time = time();
        $remark = array();
        $remark['gift_id'] = $gift->id;
        $remark['gift_name'] = $gift->name;
        $remark['gift_value'] = $gift->value;
        $remark['live_id'] = $live_id;
        $remark['uid'] = $uid;
        $remark = json_encode($remark);
        
        
        
        $transaction = new Transaction();
        
         
        $transaction->run(function()use($transaction,$uid,$live_id,$gift,$total_money,$remark,$session_id,$session)
        {
        
            $time = time();
            

            $logGiftModel = new logGiftModel();
            $logGiftModel->set_uid($uid, $live_id);
            
            $transaction->set_commit($logGiftModel);
            
            $gift_record = $logGiftModel->add($total_money, $remark, $time,$session_id,$gift->id);
            
                     
            $remark =  "用户id:[".$uid."]送礼物:[".$gift->name."]"
                ."价值[$gift->value]";
            
                       
                
             $uumoney = new moneyModel();
             $uumoney->set_uid($live_id);
             
             
             
             $uubeanModel = new moneyModel();
             $uubeanModel->set_uid($uid);
             
             
             
             $transaction->set_commit($uumoney);
             $transaction->set_commit($uubeanModel);
             
             
        
            $time = time();
            
            //主播加钱
            $money_record = $uumoney->insert(
                $total_money * \config::pay['rate'],
                $remark,
                $time,
                \configPay::money_type['gift_accept']
            );
        
                        
                
             $remark =  "用户".$uid."送主播id:[".$live_id."]礼物:["
                    .$gift->name."]"."价值[$gift->value]";
                
                    
             //观众减钱
            $uuean_record = $uubeanModel->insert(-$total_money, $remark, $money_record->id,\configPay::money_type['gift_gift']);
        
            
            //观众推荐
            $view_reference = new usersDomain($uid);
            $live_reference = new usersDomain($live_id);
            
            
            $view_reference_id =  $view_reference->get_reference();

            // if the reference id is view's id ,reference value is zero
            if($view_reference_id == $uid){
                $view_reference_id = false;
            }
            
            $live_reference_id =  $live_reference->get_reference();
            
            
            if($view_reference_id != false)
            {

                $remark =  "推荐用户".$uid."送主播id:[".$live_id."]礼物:["
                    .$gift->name."]"."价值[$gift->value]";
                
                $view_reference_money = new moneyModel();
                $view_reference_money->set_uid($view_reference_id);
                
                $transaction->set_commit($view_reference_money);
                
                $view_reference_money->insert($total_money*\config::pay['reference_view'],
                   "推荐奖励:".$remark,$uid,\configPay::money_type['reference']);
                
                
            }
            
            
            if($live_reference_id !=false)
            {
                $remark =  "用户".$uid."送推荐主播id:[".$live_id."]礼物:["
                    .$gift->name."]"."价值[$gift->value]";
                
                $live_reference_money = new moneyModel();
                $live_reference_money->set_uid($live_reference_id);
                $transaction->set_commit($live_reference_money);
                
                $live_reference_money->insert($total_money*\config::pay['reference'],
                    "推荐奖励".$remark,$live_id,\configPay::money_type['reference']);
                
    
            }
            
                
            
            
           
            $gift_info = new \stdClass();
            $gift_info->gift_img = $gift->img;
            $gift_info->gift_name = $gift->name;
            $gift_info->gift_id = $gift->id;
            $gift_info->money = $total_money;
            $gift_info->total_money =logGiftModel::give_session($session_id);
            $gift_info->view_id = $uid;
            
            //加经验值
            xpLiveModel::add($uid, $total_money*10, "礼物");
            xpUserModel::add($live_id, $total_money*10, "礼物");
            
            
            App::TxMess()->send_gift($uid, $live_id,$gift_info);
            
            
            $session->gift_money = logGiftModel::give_session($session_id);
            $session->save();
                
            return true;
             
        }, "pay:".$uid);
        
       
        return ['uubean'=>moneyModel::get_value($uid)];
    }


    public function send_gift_without_session_id($live_id,$gift_id)
    {

        $gift = giftModel::get($gift_id);

        if($gift == false)
        {
            App::Input()->error("礼物不存在");
        }

        $total_money = $gift->value;

        $user_view = new usersDomain($this->getKey());

        if( $user_view->full_info()->money < $total_money)
        {
            App::Input()->error("余额不足");
        }

        $uid = $this->getKey();

        $time = time();
        $remark = array();
        $remark['gift_id'] = $gift->id;
        $remark['gift_name'] = $gift->name;
        $remark['gift_value'] = $gift->value;
        $remark['live_id'] = $live_id;
        $remark['uid'] = $uid;
        $remark = json_encode($remark);

        $transaction = new Transaction();


        $transaction->run(function()use($transaction,$uid,$live_id,$gift,$total_money,$remark)
        {

            $time = time();


            $logGiftModel = new logGiftModel();
            $logGiftModel->set_uid($uid, $live_id);

            $transaction->set_commit($logGiftModel);

            $gift_record = $logGiftModel->add($total_money, $remark, $time,0,$gift->id);


            $remark = "用户id:[".$uid."]送礼物:[".$gift->name."]"."价值[$gift->value]";

            $uumoney = new moneyModel();
            $uumoney->set_uid($live_id);

            $uubeanModel = new moneyModel();
            $uubeanModel->set_uid($uid);

            $transaction->set_commit($uumoney);
            $transaction->set_commit($uubeanModel);

            $time = time();

            //主播加钱
            $money_record = $uumoney->insert($total_money*\config::pay['rate'],
                $remark,$time,\configPay::money_type['gift_accept']);

            $remark = "用户id:[".$uid."]送主播id:[".$live_id."]礼物:[".$gift->name."]"."价值[$gift->value]";


            //观众减钱
            $uuean_record = $uubeanModel->insert(-$total_money, $remark, $money_record->id,\configPay::money_type['gift_gift']);


            //观众推荐
            $view_reference = new usersDomain($uid);
            $live_reference = new usersDomain($live_id);

            $view_reference_id =  $view_reference->get_reference();
            $live_reference_id =  $live_reference->get_reference();


            if($view_reference_id != false)
            {

                $view_reference_money = new moneyModel();
                $view_reference_money->set_uid($view_reference_id);

                $transaction->set_commit($view_reference_money);

                $view_reference_money->insert($total_money*\config::pay['reference_view'],
                    "推荐奖励".$remark,$uid,\configPay::money_type['reference']);


            }


            if($live_reference_id !=false)
            {

                $live_reference_money = new moneyModel();
                $live_reference_money->set_uid($live_reference_id);
                $transaction->set_commit($live_reference_money);

                $live_reference_money->insert($total_money*\config::pay['reference'],
                    "推荐奖励".$remark,$live_id,\configPay::money_type['reference']);


            }


            $gift_info = new \stdClass();
            $gift_info->gift_img = $gift->img;
            $gift_info->gift_name = $gift->name;
            $gift_info->gift_id = $gift->id;
            $gift_info->money = $total_money;
            $gift_info->view_id = $uid;

            //加经验值
            xpLiveModel::add($uid, $total_money*10, "礼物");
            xpUserModel::add($live_id, $total_money*10, "礼物");


//            App::TxMess()->send_gift($uid, $live_id,$gift_info);

            return true;

        }, "pay:".$uid);


        return ['uubean'=>moneyModel::get_value($uid)];
    }




}
