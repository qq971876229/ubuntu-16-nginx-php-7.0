<?php

namespace App\Models;
use App\Lib\System\App;
use bcl\redis\numBase;



class usersStateModel extends baseModel
{

    public function getSource()
    {
        return "users";
    }
    
    
    public static function get($uid)
    {
    
        $num = new numBase("users:state:".$uid);
         
        if( $num->exists() == true)
        {
            $n =  $num->get();
             
            return $n;
        }
    

        $value =   App::TxMess()->user_state($uid);
         
        
         $num->set($value,30);
    
         return $value;
    
    }
    

    
   
  
   
}
