<?php

namespace App\Lib\System;



class App
{

   
   
    //----------
    
    private static $input_;
    
    public static function Input()
    {
    
        if(empty(self::$input_))
        {    
            self::$input_ = new Input();
        }
    
        return self::$input_;
    }
    
    

    private static $auth_;
    
    public static function Auth()
    {
        if(empty(self::$auth_))
        {
            self::$auth_= new Auth();
        }
    
        return self::$auth_;
    }
    
    private static $config_;
    
    public static function Config()
    {
        if(empty(self::$config_))
        {
            self::$config_= include APP_PATH . "/config/phconfig.php";
        }
    
        return self::$config_;
    }
    
    
    
    public static  function signature()
    {
        
        
        
        $url =  $_SERVER["QUERY_STRING"];
        
       
        //echo $url."$$";
         
         $url =  substr($url, strpos($url,"=")+2);
         

        // echo $url;
         //die();

         
         if( substr($url,0,4) == "mgr/")
         {
             return;
         }
         


             
               
          $api = ["pay/webhooks","app/live_callback","vod/check","img/upload","img/upload_file","user/send_login_sms","user/login_sms","user/get_share_info"];
        
       // echo $url;
        
       if(in_array($url, $api))
          return ;
    
       
    
        $nonce = $_GET['nonce'];
        $signature = $_GET['signature'];
    
        $data = file_get_contents("php://input");
         
        $app_key = \config::app["app_key"];
    
    
        if(isset($_GET['nonce']) == false || isset($_GET['signature']) == false)
        {
            self::Input()->error("没有传签名参数","");
        }
    
    
        if($signature !=  md5($data.$nonce.$app_key))
            self::Input()->error("签名错误","");
    }
    
    
    

    private static $tx_mess_;
    
    public static function TxMess()
    {
        
        if(empty(self::$tx_mess_))
        {
            self::$tx_mess_= new TxMess();
        }
        
        return self::$tx_mess_;
    }
    
    
    private static $moblie_;
    
    public static function Moblie()
    {
        
        if(empty(self::$moblie_))
        {
            self::$moblie_= new Moblie();
        }
        
        return self::$moblie_;
    }
 
    
    
    private static $AuthMgr_;
    
    public static function AuthMgr()
    {
    
        if(empty(self::$AuthMgr_))
        {
            self::$AuthMgr_= new AuthMgr();
        }
    
        return self::$AuthMgr_;
    }
    
    
    
    
    
    private static $log_;
    
    public static function Log()
    {
    
        if(empty(self::$log_))
        {
            self::$log_= new Log();
        }
    
        return self::$log_;
    }
    
    private  static $img_;
    
    public static function Img()
    {
        if(empty(self::$img_))
        {
            self::$img_= new Img();
        }
    
        return self::$img_;
    }
    
    
  

}

