<?php

namespace App\Domain;
use App\Models\usersModel;
use bcl\redis\cacheBase;
use App\Models\xpUserModel;
use App\Models\rankUserModel;
use App\Models\moneyModel;
use App\Models\usersStateModel;
use App\Lib\System\App;
use App\Models\vodSessionModel;



/*
{
    "xp_user": 0,
    "rank_user": 1,
    "uumoney": 0,
    "uubean": "7",
    "get_uubean": 0,
    "give_uubean": 0,
    "is_real_name": 0,
    "real_name": "",
    "moblie": "",
    "verified_reason": ""
}
*/

class rankListDomain  extends baseDomain
{
    
    
   public function __construct($type)
   {
       $this->setkey((string)$type);
   }
 
   public static function get_appstore()
   {
       $list =  self::get("appstore",[]);
       
       shuffle($list);
       
       return $list;
       
   }

    /**
     * get host by the type
     * rank by stick first,free second order by answer rate,
     * @param $host_type
     * @return array
     */
   public static function get_by_host_type($host_type,$page=1){


       $http_host = $_SERVER['HTTP_HOST'];

       if ($http_host == 'testapi.miyintech.com') {

           $pageNum = 20;

       } elseif ($http_host == 'qlapi.miyintech.com') {

           $pageNum = 30;

       } elseif ($http_host == 'ymapi.miyintech.com') {

           $pageNum = 30;

       } else {

           $pageNum = 30;
       }




       $pageStart = ($page-1)*$pageNum;
       $pageEnd = $page*$pageNum;

       $sql = "select id from users  where auth_state = 2 AND host_type='".$host_type."'   order by stick desc, stick_time desc, request_time desc , answer_rate desc,id desc LIMIT $pageStart,$pageEnd";

       $value = usersModel::DB()->getAll($sql);

       $list =  usersDomain::format_simple_user_list($value,"id");



       foreach($list as $k=>$v){

           if($v->online_state == 20){
               unset($list[$k]);
           }

           // remove the birthday
           unset($list[$k]->birthday);
//           $list[$k]->birthday = 0;
       }




       return $list;

//       return self::get($host_type,$list);

   }

    /**
     * let the free first
     * @param $list
     * @return mixed
     */
   public static function rankUsersByAnswerRate($list){
       foreach($list as $k=>$v){
            if($v->online_state == 40){
                array_unshift($list,$v);
                unset($list[$k]);
            }
       }

       return $list;

   }


   
   public static function get_hot()
   {

       $path = "index:hot";
       return cacheBase::get($path,function()
       {
          $list = usersDomain::random(20);
          return self::get("hot",$list);
       
       
       },\configCache::user['index']);
       
       
       
       
   }
   
   public static function get_goddess()
   {
       
       
       $path = "index:goddess";
       return cacheBase::get($path,function()
       {
          $list =  usersDomain::random(20);
       
        return self::get("goddess",$list);
            
            
       },\configCache::user['index']);
       
   }
   
   public static function get_new()
   {
        
      
       $path = "index:new";
       return cacheBase::get($path,function()
       {
           $value = usersModel::DB()->getAll("select id from users  where auth_state = 2   order by id desc LIMIT 0,20");
        
             $list =  usersDomain::format_simple_user_list($value,"id");
        
            return self::get("new",$list);
       
       
       },\configCache::user['index']);
       
        
   }
   
   
   
   
   
   public static function get($type,$list)
   {
       
       $path = "ranklist:".$type.":*";
       
       
       $rank_list =  \bcl\redis\base::get_rd()->keys($path);
       
       
       
       $user_id_list  = [];
       
       foreach ($rank_list as $k=>$v)
       {
            
           $r = json_decode(\bcl\redis\base::get_rd()->get($v));
           
           $r->id = $r->uid;
           
           unset($r->uid);
            
           $user_id_list[] = $r;
       }
       
       
       /*
       
       usort($user_id_list, function($a, $b) 
       {
           if( $a->time == $b->time ) 
               return 0;
           
           if($a->time > $b->time)
               return -1;
           
               
            if($a->time < $b->time)
                 return 1;
               
       });
       
       
       
       
       
       foreach ($user_id_list as $x)
       {
           
           foreach ($list as $k=>$y)
           {
               
               
               if( $y->id == $x->id)
               {
                   unset($list[$k]);
               }
           }
           
       }*/
       
//            usort($list, function($a, $b)
//            {
//
//                if($a->online_state == $b->online_state)
//                    return 0;
//
//                    if($a->online_state > $b->online_state)
//                        return -1;
//
//                        if($a->online_state < $b->online_state)
//                            return 1;
//
//            });
//
     
       
       $user_id_list =  usersDomain::format_simple_user_list($user_id_list,"id");
       
       $user_id_list = array_merge($user_id_list,$list);
 
       
       return $user_id_list;
   }
   
   
   public static function add($type,$uid,$date)
   {
       
       $user = new usersDomain($uid);
       
       if($user->simple_info()->is_live == 0)
       {
           App::Input()->error("主播才能置顶");
       }
       
       
       $path = "ranklist:".$type.":".$uid;
       
       
       $r = new \stdClass();
       
       $r->time = time();
       $r->uid = $uid;
       
  
       \bcl\redis\base::get_rd()->set($path,json_encode($r));
        
        
       $ttl =  strtotime($date) - time() ;
        
       
        
       \bcl\redis\base::get_rd()->expire($path,$ttl);
       
   }
   
   
  
    
    
   
      
   
}
