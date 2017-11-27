<?php

namespace App\Lib\System;

use App\Models\usersModel;
use App\RModels\tokenRModel;
use App\Domain\usersDomain;
use App\Lib\Tim\TLSSigAPI;
use App\Lib\System\App;
use App\RModels\userTimeCountRModel;


class Auth
{

    
    private $uid_;
    
    
    public  function auth($uid)
    {
             
        $this->uid_ = $uid;
        $token =   tokenRModel::create_token($uid);   
        
        $user = new usersDomain($uid);
       
          
        $data = new \stdClass();
        $data->info = $user->full_info();

        $config = $this->get_config();
        
        $config->token = $token->getKey();
        
        $data->config = $config;
        
        
        
        return $data;
    }
    
    
    public  function get_config()
    {
        if(empty($this->uid_))
        {
           \App::get()->Input->error("没有登陆");
        }
   
        $account_id =  $this->uid_;
    
        $config = new \stdClass();
    
        $app_config = \config::app;
         
        $config->app_name = $app_config['app_name'];
        
        $config->ver = $app_config['ver'];
        
        
        $sig = TLSSigAPI::createSig($this->uid_);
     
        $config->sig = $sig;
        
        
        $config->app_id=$app_config['live_app_id'];
        $config->account_type = $app_config['live_account_type'];
         
        $config->expire= time()+3600*24*3;//3天保存
         
        $config->img_url = $app_config['img_bucket_url'];
        $config->file_url = $app_config['file_bucket_url'];
        
        $config->live_url = \config::app['live_url'];
       
    
        return $config;
    
    }
    
   
    public  function authLogin($heartbeat=0)
    {
        $uid = $this->is_login();
         
        if( $uid == false)
        {
            App::Input()->error("账号过期",100);
        }
        
        userTimeCountRModel::count($uid);

        //update the request time
        if($heartbeat==1){
            $update_request_time = usersModel::findFirst($uid);
            $update_request_time->request_time = 0;
            $update_request_time->save();
        }else{

            $update_request_time = usersModel::findFirst($uid);
            $update_request_time->request_time = time();
            $update_request_time->save();
        }

        $user  = new usersDomain($uid);
        
        if($user->full_info()->is_black == 1)
        {
            App::Input()->error("账号被封禁，禁止止登陆",101);
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
         
        $token  = new tokenRModel($token,null,0);
                        
        if($token->load() == false)
        {            
            return false;
        }
         
        $this->uid_= $token->getKey();
    
        return $token->uid;
    
    }
     
   
   
   
    
   
}
