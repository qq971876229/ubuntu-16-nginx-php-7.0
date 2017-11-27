<?php

namespace App\Domain;
use App\Models\usersModel;
use App\Models\realNameModel;
use App\RModels\user_simple_RModel;
use App\RModels\user_extend_RModel;
use App\Models\xpUserModel;
use App\Models\rankUserModel;
use App\Models\moneyModel;
use App\Models\uubeanModel;
use App\Models\logGiftModel;
use App\Lib\System\App;
use App\Models\linkModel;
use App\MModels\findMModel;




class findDomain  extends baseDomain
{
    
    
   public function __construct($uid)
   {
       $this->setkey($uid);
   }
 
  
   

   
   public  function comment_add($fid,$p_uid,$content)
   {
       $data = findMModel::comment($fid,$this->getKey(),$p_uid,$content);
       
       $this->format_comment($data);
       
       return $data;
       
   }
   
   
   private   function format_comment($comment)
   {
       foreach ( $comment as $k=>$v)
       {
            
           $user = new usersDomain($comment[$k]->uid);
           $comment[$k]->nickname = $user->simple_info()->nickname;
           $comment[$k]->p_nickname = "";
            
           if($comment[$k]->p_uid != null)
           {
               $p_user = new usersDomain($comment[$k]->p_uid);
                
               $comment[$k]->p_nickname = $p_user->simple_info()->nickname;
           }
            
       }
       
   }
   
   private function format_find($list)
   {
       
       usersDomain::format_simple_user_list($list,"uid");
       
       $my_user = new usersDomain($this->getKey());
       
       
       $my_uid = $this->getKey();
       foreach ($list as $k=>$v)
       {
       
           $list[$k]->like_num = count($list[$k]->like);
            
           $list[$k]->is_follow = $my_user->is_follow($v->uid);
      
           $like_list = $list[$k]->like;
           $list[$k]->is_like = 0;
           foreach ($like_list as $x=>$y)
           {
               if($y->uid == $my_uid)
               {
                   $list[$k]->is_like = 1;
                   break;
               }
              
           }
           
      
           unset($list[$k]->like);
       
           $comment = $list[$k]->comment;
           
           $this->format_comment($comment);
                 
       }
       
       return $list;
       
   }
   
   
   public function get_list_page($page) 
   {
       $list = findMModel::get_list_page($page);
         
       return $this->format_find($list);
       
   }
   
   public function get_list_user_page($uid,$page)
   {
       
       $list = findMModel::get_list_user_page([$uid],$page);
        
       return $this->format_find($list);
        
   }
   
   public function get_list_follow_page($page)
   {
       
      $follow_uid = linkModel::get_follow_list($this->getKey());
       $list = findMModel::get_list_user_page($follow_uid,$page);
   
       return $this->format_find($list);
   
   }
   
   public function get_list_round_page($lng,$lat,$page)
   {
       
       $follow_uid = linkModel::get_follow_list($this->getKey());
       $list = findMModel::get_list_round_page($lng,$lat,$page);
        
       return $this->format_find($list);
   }
   
   
 
  
}
