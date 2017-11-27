<?php

namespace App\RModels;


class logRModel extends \bcl\redis\objectBase
{
    

    public function getSource()
    {
        return "log";
    }
    
        
    public static function log($path,$mess)
    {
        self::get_rd()->set("log:".$path.":".microtime(true),$mess);
    }
    
    
   
    

        

}
