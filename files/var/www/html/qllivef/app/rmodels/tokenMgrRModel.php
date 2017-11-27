<?php

namespace App\RModels;


class tokenMgrRModel extends \bcl\redis\objectBase
{
    

    public function getSource()
    {
        return "token_mgr";
    }
    
        
    public  static function create_token($uid)
    {
       
        $cip = $_SERVER["REMOTE_ADDR"];//ip
         
        $cur_time = time();
         
        $time = $cur_time+3600*24;
        
        $token = md5($cip.$uid.time().rand(1,100000));
        
        $token_obj = new tokenMgrRModel($token,null,0);
                
        $token_obj->uid = $uid;        
      
        $time = time() ;
        
        $token_obj->expired = $time+\configCache::user['token_mgr'];
        $token_obj->add_time = $time;
        $token_obj->update_time = $time;
        
        
        $token_obj->set(null,\configCache::user['token_mgr']);
      
        return $token;
        
    }
    

        

}
