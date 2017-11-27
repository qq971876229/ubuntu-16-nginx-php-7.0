<?php

namespace App\Models;

use Phalcon\Mvc\Model;
use Phalcon\Mvc\Model\Resultset\Simple as Resultset;
use App\Bll\Input;
use App\Lib\Geohash;
use App\App;
use App\Cache\keyCache;
use bcl\redis\cacheBase;
use bcl\redis\numBase;


class xpUserModel extends baseModel
{
  
   
    public function getSource()
    {
        return "xp_user";
    }
    
    
    public static function get($uid)
    {
                        
       $num = new numBase("users:xp_user:".$uid);
       
       if( $num->exists() == true)
       {
           $n =  $num->get();
           
           return $n;
       }
          
        
      $value = self::DB()->getOne("select sum(value) from xp_user where uid=?", array($uid));
            
      if($value == false)
          $value = 0;
      
      $num->set($value);
      
      return $value;
              
    }
    
    
    public static  function add($uid,$value,$remark)
    {
        $m = new xpUserModel();
         
        $m->uid = $uid;
        $m->remark = $remark;
        $m->value = $value;
        $m->add_time = time();
        $m->save();
        
        $num = new numBase("users:xp_user:".$uid);
        $num->add($value);
         
    }
    
   
 
}
