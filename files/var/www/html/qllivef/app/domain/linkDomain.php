<?php

namespace App\Domain;
use App\Lib\System\App;
use App\Models\linkModel;





class linkDomain  extends baseDomain
{
    
    
   public function __construct($uid)
   {
       $this->setkey($uid);
   }
 
    
   public function get_friend_list($page)
   {
       $l =  linkModel::get_friend_list($this->getKey());
        
       $list = usersDomain::format_simple_user_list($l,null);
        
       $list = $this->page_array($list, $page);
          
       return $list;
   }
   
   public function get_black_list($page)
   {
       $l =  linkModel::get_black_list($this->getKey());
   
       $list = usersDomain::format_simple_user_list($l,null);
   
       $list = $this->page_array($list, $page);
   
       return $list;
   }
   
   
   
   public function get_fans_list($page)
   {
       $l =  linkModel::get_fans_list($this->getKey());
   
       $list = usersDomain::format_simple_user_list($l,null);
   
       $list = $this->page_array($list, $page);
   
   
   
       $follow_list = linkModel::get_follow_list($this->getKey());
   
       foreach ($list as $k=>$v)
       {
           $list[$k]->l_uid = $this->getKey();
   
           if(in_array($v->uid, $follow_list))
           {
               $list[$k]->is_follow = 1;
           }
           else
           {
               $list[$k]->is_follow = 0;
           }
   
   
       }
       
       return $list;
   }
  
   
   
 
   public function get_follow_list($page)
   {
      $l =  linkModel::get_follow_list($this->getKey());
      
      $list = usersDomain::format_simple_user_list($l,null);
      
      $list = $this->page_array($list, $page);
      
      App::Input()->out($list);
      
      die();
      
      
      
      $friend_list = linkModel::get_friend_list($this->getKey());
      
      $notify_list = linkModel::get_notify_list($this->getKey());
      
      
      foreach ($list as $k=>$v)
      {
          $list[$k]->l_uid = $list[$k]->uid;
          $list[$k]->uid = $this->getKey();
          
          if(in_array($v->l_uid, $friend_list))
          {
              $list[$k]->is_friend = 1;
          }
          else 
          {
              $list[$k]->is_friend = 0;
          }
          
          if(in_array($v->l_uid, $notify_list))
          {
              $list[$k]->is_notify = 1;
              $list[$k]->notify = 1;
          }
          else
          {
              $list[$k]->is_notify = 0;
              $list[$k]->notify = 0;
          }
          
          
      }
      
      
      
      return $list;
   }
      
   
}
