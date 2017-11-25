<?php

namespace App\Models;

use Phalcon\Mvc\Model;
use Phalcon\Mvc\Model\Resultset\Simple as Resultset;
use App\Bll\Input;
use App\Lib\tim\TimRestAPI;
use App\App;
use App\model\uubean;
use App\Models\logGiftModel;
use App\Cache\keyCache;
use App\Models\roomModel;
use bcl\redis\cacheBase;



class giftModel extends baseModel
{
  
   
    public function getSource()
    {
        return "gift";
    }
    
    
   
    public static function get_all($is_show)
    {
        
        
        return cacheBase::get("share:gift_list",function()use($is_show)
        {       
            $list =  giftModel::query()->where("is_show =$is_show")->order("sort")->execute();
            
            return $list;

        
        },\configCache::user['token']);
        
            
    }
    
    
    public static function get($id)
    {
       $list = self::get_all(1);
       
       foreach ($list as $v)
       {
           if( $v->id == $id)
               return $v;
       }
    
        return false;
    }
    
  
    
   
}
