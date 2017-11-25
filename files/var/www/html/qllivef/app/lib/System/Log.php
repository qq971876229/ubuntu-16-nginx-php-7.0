<?php
namespace App\Lib\System;
use App\MModels\baseMModel;

class Log
{
    
    
     public function write($path,$type,$mess)
     {
         
         $request = file_get_contents("php://input");
         
         $request = json_decode($request);
         
         baseMModel::insert("log.live", $request);
     }
    
    
}