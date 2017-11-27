<?php

namespace App\Models;

use Phalcon\Mvc\Model;
use App\Bll\API;
use Phalcon\Mvc\Model\Resultset\Simple as Resultset;
use bcl\redis\cacheBase;


class rankUserModel extends baseModel
{
    //更新缓存
    public function  update_cache()
    {
        self::RDB()->del('rank_user');
    
        self::get_rank();
    
    }
    
    
    public function getSource()
    {
        return "rank_user";
    }
    


        
    public static function get_user_rank($user_info)
    {
      
      
        return cacheBase::get("users:rank_user:".$user_info->id,function()use($user_info)
        {
        
            $xp = 0;
                    
            if($user_info->is_live == 0)
            {
                //echo "user";
                $xp = xpUserModel::get($user_info->id);
            }
            else
            {
                
               // echo "live";
                $xp = xpLiveModel::get($user_info->id);
            }
            
            return self::xp2rank($xp);
        
        
        },\configCache::user["info"]);
        
        
        
        return 1;
        
        
    }
       
    
    public static function xp2rank($xp)
    {
        $rank = self::get_rank();
        
                        
        foreach ($rank as $v)
        {
             if($xp < $v->xp)
             {
                 return $v->rank -1;
             }
        }
        
    }
    
   
    public static function get_rank()
    {
        
        
        return cacheBase::get("share:rank_user",function()
        {

            $list =  rankUserModel::DB()->getAll("select * from rank_user order by rank");
                        
            return $list;
        
        },\configCache::share['base']);
          
    }

  
}
