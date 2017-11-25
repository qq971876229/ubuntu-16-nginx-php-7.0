<?php

namespace App\RModels;


class tokenRModel extends \bcl\redis\objectBase
{
    

    public function getSource()
    {
        return "token";
    }
    
        
    public  static function create_token($uid)
    {
       
        $cip = $_SERVER["REMOTE_ADDR"];//ip
         
        $cur_time = time();
         
        $time = $cur_time+3600*24*60;
        
        $token = md5($cip.$uid.time().rand(1,100000));
        
        $token_obj = new tokenRModel($token,null,0);
                
        $token_obj->uid = $uid;        
      
        $time = time() ;
        
        $token_obj->expired = $time+\configCache::user['token'];
        $token_obj->add_time = $time;
        $token_obj->update_time = $time;
        
        
        $token_obj->set(null,\configCache::user['token']);
      
        return $token_obj;
        
    }
    

        

}
