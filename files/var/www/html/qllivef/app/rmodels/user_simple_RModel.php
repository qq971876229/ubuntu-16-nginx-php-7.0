<?php

namespace App\RModels;



class user_simple_RModel extends \bcl\redis\objectBase
{
    
    public function getSource()
    {
        return "users:user_simple";
    }
    
    
    public static function get($uid) 
    {
        ;
    }
    
  
}
