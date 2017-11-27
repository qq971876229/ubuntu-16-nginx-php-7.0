<?php
namespace App\Controllers;
use App\Models\giftModel;
use App\Lib\System\App;
use App\Domain\GiftDomain;
use App\Models\logGiftModel;
use App\Models\vodSessionModel;
use App\Domain\usersDomain;


class GiftController extends baseController
{
    
    
 
    
    
    public function sendAction()
    {
        $uid = App::Auth()->authLogin();
        
        $live_id = App::Input()->get("live_id");
        
        $gitf_id = App::Input()->get("gitf_id");
        $session_id = App::Input()->get("session_id");
        
        
        
        $gift  = new GiftDomain($uid);
        
        $gift->send_gift($live_id, $gitf_id,$session_id);
        
        App::Input()->out("ok");
    }
    
    public function give_listAction()
    {
        
        App::Auth()->authLogin();
        
        $uid = App::Input()->get("uid");
       
        
        $data = logGiftModel::give_list($uid);
        
        App::Input()->out($data);
        
    }
    
    public function recipient_listAction()
    {
        App::Auth()->authLogin();
        
        $uid = App::Input()->get("uid");
        
        $data = logGiftModel::recipient_list($uid);
        
        App::Input()->out($data);
    }
    
    public function gift_infoAction()
    {
        $uid = App::Auth()->authLogin();
        
        $data = new \stdClass();
        
        $gift = giftModel::get_all(1);
        
        
        $session_id = App::Input()->get("session_id");
        
        $session = vodSessionModel::get($session_id);
        
        if($session == false)
        {
            App::Input()->error("会话不存在");
        }
        
        
        if( $session->view_id != $uid)
        {
            App::Input()->error("观众不在会话中");
        }
        
        $occupied_money = GiftDomain::occupied_money($session);
        
        
        $user = new usersDomain($uid);
        
        $money = $user->full_info()->money - $occupied_money;
        
        $data->gift = $gift;
        $data->money = $money;
        
        
        App::Input()->out($data);
        
    }


    /**
     * the user get the gift list without session id
     */
    public function gift_info_without_session_idAction()
    {
        $uid = App::Auth()->authLogin();

        $data = new \stdClass();

        $gift = giftModel::get_all(1);

        $user = new usersDomain($uid);

        $money = $user->full_info()->money;

        $data->gift = $gift;
        $data->money = $money;

        App::Input()->out($data);

    }

    /**
     * send the gift to the host without session id
     */
    public function send_without_session_idAction()
    {
        $uid = App::Auth()->authLogin();

        $live_id = App::Input()->get("live_id");

        $gift_id = App::Input()->get("gift_id");

        $gift  = new GiftDomain($uid);

        $gift->send_gift_without_session_id($live_id, $gift_id);

        App::Input()->out("ok");
    }



  
}

