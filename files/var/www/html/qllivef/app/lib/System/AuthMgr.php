<?php

namespace App\Lib\System;

use App\RModels\tokenMgrRModel;
use App\Lib\System\App;


class AuthMgr
{

    
    private $uid_;
    
    
    public  function auth($uid)
    {
             
        $this->uid_ = $uid;
        $token =   tokenMgrRModel::create_token($uid);   
        
        return $token;
    }
    
    public  function authLogin()
    {
        $uid = $this->is_login();
         
        if( $uid == false)
        {
            App::Input()->error("账号过期",100);
        }
    
        return $uid;
    }
     
    
     
    private $token_;
    public function get_token()
    {
        return $this->token_;
    }
    
     
    public  function is_login()
    {
        $token = App::Input()->get("token");
        
        $this->token_ = $token;
         
        $token  = new tokenMgrRModel($token,null,0);
                        
        if($token->load() == false)
        {            
            return false;
        }
         
        $this->uid_= $token->getKey();
    
        return $token->uid;
    
    }
     
   
   
   
    
   
}
