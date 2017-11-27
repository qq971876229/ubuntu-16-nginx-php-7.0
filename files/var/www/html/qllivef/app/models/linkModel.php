<?php

namespace App\Models;
use Phalcon\Mvc\Model;
use Phalcon\Mvc\Model\Resultset\Simple as Resultset;
use App\RModels\usersRModel;
use App\RModels\users_simple_RModel;
use Phalcon\Mvc\Model\Behavior\Timestampable;
use bcl\redis\cacheBase;
use App\Lib\System\App;
use App\Domain\usersDomain;






class linkModel extends baseModel
{
    
    public function getSource()
    {
        return "link";
    }
    
    
    private static  $path_ = "users:link:";
    
 
    
    
    public static function del($uid,$l_uid)
    {
        $list = linkModel::find("uid = '$uid' and l_uid='$l_uid'");
    
        foreach ($list as $v)
        {
            $v->delete();
        }
        
        self::clear_cache($uid);
        self::clear_cache($l_uid);
    }
    
    
    
public static function live_notify($uid,$l_uid)
    {
        
        $link = linkModel::findFirst("uid = '$uid' and l_uid='$l_uid'");
        
        if($link == false)
        {
            App::Input()->error("没有关注");
        }
        
         if( $link->notify == 0)
         {
             $link->notify = 1;
         }
         else 
         {
             $link->notify = 0;
         }
         
         $link->save();
         
         
         cacheBase::del(self::$path_."notify:".$uid);
 
         
       
         $list = linkModel::find("uid = '$uid' and notify='1'");
         
         
         $notify_list = array();
         foreach ($list as $v)
         {
             $notify_list[] = $v->l_uid;
         }
         
        
         return $link->notify ;
        
    }
    
     public static function add($uid,$l_uid,$type=0)
    {
        
        if($uid == $l_uid)
        {
            App::Input()->error("不能关注自己");
        }
        
        
        
        $user = new usersDomain($uid);
        $user_l = new usersDomain($l_uid);
        
       
        if($user->exists() == false)
        {
            App::Input()->error("用户不存在");
        }
        
        if($user_l->exists() == false)
        {
            App::Input()->error("黑名单用户不存在");
        }
        
                
         self::del($uid,$l_uid);
        
      
        $l = new linkModel();
        $l->uid = $uid;
        $l->l_uid = $l_uid;
        $l->type = $type;
        $l->add_time = time();
        $l->save();
        
        self::clear_cache($uid);
        self::clear_cache($l_uid);
  
    } 
    
    
    public  static function clear_cache($uid)
    {
        
        $k = self::$path_."follow:".$uid;
        $k1 = self::$path_."fans:".$uid;
        $k2 = self::$path_."friend:".$uid;
        $k3 = self::$path_."black:".$uid;
        $k4 = self::$path_."link_num:".$uid;
        
       cacheBase::del($k);
       cacheBase::del($k1);
       cacheBase::del($k2);
       cacheBase::del($k3);
       cacheBase::del($k4);
    }
    
    
    public static function get_link_num($uid)
    {
        
        return cacheBase::get(self::$path_."link_num:".$uid, function() use ($uid)
        {
            
            $num = new \stdClass();
            $num->follow_num = count(self::get_follow_list($uid));
            $num->fans_num  =  count(self::get_fans_list($uid));
            $num->friend_num = count(self::get_friend_list($uid));
            $num->black_num = count(self::get_black_list($uid));
            
            return $num;
                
        },
            \configCache::user["info"]);
        
        
    }
    
    
    public static function get_friend_list($uid)
    {
        
        return cacheBase::get(self::$path_."friend:".$uid, function() use ($uid)
        {
            $sql = "SELECT t1.l_uid FROM (SELECT * FROM link WHERE uid = ? and type=0)  AS t1 INNER JOIN link t2 ON (t1.uid = t2.l_uid and t1.l_uid = t2.uid) order by t1.id desc";
         $list =  self::DB()->getAll($sql,array($uid));
         if($list == false)
             return array();
         
         
            $friend_list = array();
            foreach ($list as $v)
            {
                $friend_list[] = $v->l_uid;
            }
            
            return $friend_list;
                
        },
            \configCache::user["info"]);
            
    }
    
    
    
    public static function get_follow_list($uid)
    {
    
        return cacheBase::get(self::$path_."follow:".$uid, function() use ($uid)
        {
            $list =  self::DB()->getAll("select l_uid from link where uid = ? and type=0 order by id desc",array($uid));
        
            if($list == false)
                return array();
            
            
            $follw_list = array();
            foreach ($list as $v)
            {
                $follw_list[] = $v->l_uid;
            }
        
            return $follw_list;
        
        },
        \configCache::user["info"]);
        
    }
    
    public static function get_black_list($uid)
    {
    
        return cacheBase::get(self::$path_."black:".$uid, function() use ($uid)
        {
            $list =  self::DB()->getAll("select l_uid from link where uid = ? and type=1 order by id desc",array($uid));
        
            
            if($list == false)
            {
                return array();
            }
                
            
            $black_list = array();
            

                       
            foreach ($list as $v)
            {
                $black_list[] = $v->l_uid;
            }
            

            
            return $black_list;
        
        },
        \configCache::user["info"]);
    }
    
    public static function get_fans_list($uid)
    {
    
       return cacheBase::get(self::$path_."fans:".$uid, function() use ($uid)
        {
            $list =  self::DB()->getAll("select uid from link where l_uid = ?  and type=0 order by id desc",array($uid));
        
            if($list == false)
                return array();
            
            
            $fans_list = array();
            foreach ($list as $v)
            {
                $fans_list[] = $v->uid;
            }
            
            return $fans_list;
        
        },
        \configCache::user["info"]);
    }
    
    public static function get_notify_list($uid)
    {
    
        
        return cacheBase::get(self::$path_."notify:".$uid, function() use ($uid)
        {
            $list =  self::DB()->getAll("select l_uid from link where uid = ?  and notify=1 order by id desc",array($uid));
    
            if($list == false)
                return array();
    
    
                $fans_list = array();
                foreach ($list as $v)
                {
                    $fans_list[] = $v->l_uid;
                }
    
                return $fans_list;
    
        },
        \configCache::user["info"]);
    }
    
    
   
}
