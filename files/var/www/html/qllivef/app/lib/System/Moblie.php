<?php

namespace App\Lib\System;
use App\Models\smsModel;
use Pingpp\Error\Api;


class Moblie 
{
    
    
    
    
    
    public  function send_real_name_code($phone)
    {
        return $this->send_code($phone, 2);
    }
     
    public  function check_real_name_code($phone,$code)
    {
        return $this->check_code($phone, $code,2);
    }
    
    
    
    
    

    public  function send_login_code($phone)
    {
        return $this->send_code($phone, 1);
    }

    public  function send_login_code2($phone)
    {
        return $this->send_code2($phone, 1);
    }
     
    public  function check_login_code($phone,$code)
    {
        return $this->check_code($phone, $code,1);
    }
    
    
    //发验证码
    public  function send_code($moblie,$type)
    {
    
        if(smsModel::get($moblie,$type))
        {
            App::Input()->error("2分钟内不能重复发送");
        }
    
    
       $code =  smsModel::add($moblie,$type);
       
       
       
    
        $content = "【". \config::app["moblie_app_name"]."】您的验证码是".$code;
                 
        $this->sendSMS($moblie, $content);
    }

    //发验证码
    public  function send_code2($moblie,$type)
    {

        if(smsModel::get($moblie,$type))
        {
            echo $_GET['callback']."(".json_encode('2分钟内不能重复发送').")";exit;
        }


        $code =  smsModel::add($moblie,$type);

        $content = "【". \config::app["moblie_app_name"]."】您的验证码是".$code;

        $this->sendSMS2($moblie, $content);
    }
    
    public  function check_code($moblie,$code,$type)
    {
        $sms = smsModel::get($moblie,$type);
        
        if($sms == false)
            return false;
    
        return ($sms->code == $code);
    }
    
    
     
    private   function sendSMS($mobile, $content, $time = '', $mid = '')
    {
        //$content = iconv('utf-8', 'gbk', $content);
        $http = 'https://sms.yunpian.com/v2/sms/single_send.json'; // 短信接口
        $apikey = \config::app["moblie_key"]; // 用户账号
    
    
        $data = array(
            'apikey' => $apikey, // 用户账号
            'mobile' => $mobile, // 号码
            'text' => $content, // 内容
            'time' => $time, // 定时发送
            'mid' => $mid
        );
        
        //echo $content;
        
        $re = self::postSMS($http, $data); // POST方式提交
    
        $out = array();
        preg_match('/(\{)(.*)(\})/i', $re, $out);
    
    
        $out = json_decode($out[0],true);
    
        if(trim($out['code']) == 0)
        {
            return true;
        }
        else
        {
            App::Input()->error($out['msg']);
        }
    }

    private   function sendSMS2($mobile, $content, $time = '', $mid = '')
    {
        //$content = iconv('utf-8', 'gbk', $content);
        $http = 'https://sms.yunpian.com/v2/sms/single_send.json'; // 短信接口
        $apikey = \config::app["moblie_key"]; // 用户账号


        $data = array(
            'apikey' => $apikey, // 用户账号
            'mobile' => $mobile, // 号码
            'text' => $content, // 内容
            'time' => $time, // 定时发送
            'mid' => $mid
        );

        //echo $content;

        $re = self::postSMS($http, $data); // POST方式提交

        $out = array();
        preg_match('/(\{)(.*)(\})/i', $re, $out);


        $out = json_decode($out[0],true);

        if(trim($out['code']) == 0)
        {
            return true;
        }
        else
        {

            echo $_GET['callback']."(".json_encode($out['msg']).")";exit;

            App::Input()->error($out['msg']);
        }
    }
    
    private  function postSMS ($url, $data = '')
    {
        $row = parse_url($url);
        $host = $row['host'];
        $port =  80;
        $file = $row['path'];
        $post = "";
    
        while(list($k, $v) = each($data))
        {
            $post .= rawurlencode($k) . "=" . rawurlencode($v) . "&"; // 转URL标准码
        }
        $post = substr($post, 0, - 1);
        $len = strlen($post);
        $fp = @fsockopen($host, $port, $errno, $errstr, 10);
        if(! $fp)
        {
            return "$errstr ($errno)\n";
        }
        else
        {
            $receive = '';
            $out = "POST $file HTTP/1.1\r\n";
            $out .= "Host: $host\r\n";
            $out .= "Content-type: application/x-www-form-urlencoded\r\n";
            $out .= "Connection: Close\r\n";
            $out .= "Content-Length: $len\r\n\r\n";
            $out .= $post;
            fwrite($fp, $out);
            while(! feof($fp))
            {
                $receive .= fgets($fp, 128);
            }
            fclose($fp);
            $receive = explode("\r\n\r\n", $receive);
    
    
            unset($receive[0]);
            return implode("", $receive);
        }
    }
   
    
   
}
