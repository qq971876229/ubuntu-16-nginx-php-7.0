<?php

namespace App\Controllers;
use App\Models\linkModel;
use App\Lib\System\App;
use App\Domain\linkDomain;
use App\Domain\usersDomain;
use App\Models\usersModel;





class LinkController extends baseController
{

    public function user_infoAction()
    {
        $uid = App::Auth()->authLogin();
        $list = App::Input()->get("list");
        
        $user = new usersDomain($uid);
        
        App::Input()->out($user->get_user_info_list($list));
        
    }
    
   
    
    public function addAction()
    {
        
        $uid = App::Auth()->authLogin();
        $l_uid = App::Input()->get("uid");
        
        linkModel::add($uid, $l_uid);
        
        App::Input()->out("ok");
        
    }
    
    
    public function black_listAction()
    {
    
        $uid = App::Auth()->authLogin();
        

        $page = App::Input()->get("page");
        
        $link = new  linkDomain($uid);
        
        App::Input()->out($link->get_black_list($page));
        
    }
    
 
    public function add_blackAction()
    {
    
        $uid = App::Auth()->authLogin();
        $l_uid = App::Input()->get("uid");
    
        linkModel::add($uid, $l_uid,1);
    
        App::Input()->out("ok");
    
    }
    
    public function del_blackAction()
    {
    
        $uid = App::Auth()->authLogin();
        $l_uid = App::Input()->get("uid");
    
        linkModel::del($uid, $l_uid);
    
        App::Input()->out("ok");
    
    }
    
    public function delAction()
    {

        $uid = App::Auth()->authLogin();
        $l_uid = App::Input()->get("uid");
    
        linkModel::del($uid, $l_uid);
    
        App::Input()->out("ok");
    
    }
    
    public function follow_listAction()
    {
        $uid = App::Auth()->authLogin();
        
   
        $page = App::Input()->get("page");
        
        $link = new  linkDomain($uid);
        
        App::Input()->out($link->get_follow_list($page));
    }
    
    public function friend_listAction()
    {
        $uid = App::Auth()->authLogin();
        
        $page = App::Input()->get("page");
        
        $link = new  linkDomain($uid);
        
        App::Input()->out($link->get_friend_list($page));
        
    }
    
    public function get_fans_listAction()
    {
        App::Auth()->authLogin();
    
        $uid  = App::Input()->get("uid");
        $page = App::Input()->get("page");
    
        $link = new  linkDomain($uid);
    
        App::Input()->out($link->get_fans_list($page));
    }
    
    public function live_notifyAction()
    {
        $uid = App::Auth()->authLogin();
        $n_uid  = App::Input()->get("uid");
             
        App::Input()->out(linkModel::live_notify($uid,$n_uid));
        
    }
  
    
    
}
