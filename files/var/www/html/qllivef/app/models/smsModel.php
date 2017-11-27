<?php

namespace App\Models;

use Phalcon\Mvc\Model;
use App\Bll\API;
use Phalcon\Mvc\Model\Resultset\Simple as Resultset;


class smsModel extends baseModel
{
  
    public function getSource()
    {
        return "sms";
    }
   
    
    public static function get($moblie,$type)
    {
        $time = time();
        
        $list = smsModel::find("expire<'$time'");
        foreach ($list as $r)
        {
            $r->delete();
        }
        
        
        return smsModel::findFirst("moblie = '$moblie' and $type='$type'");
    }

    public static  function add($moblie,$type)
    {
        $code = rand(100000,999999);
        
        
        $sms = new smsModel();
        $sms->moblie = $moblie;
        $sms->code = $code;
        $sms->type= $type;
        $sms->expire = time()+120;
        $sms->save();
        
        
        return $code;
    }
  
}
