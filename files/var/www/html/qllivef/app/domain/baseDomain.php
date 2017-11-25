<?php

namespace App\Domain;
use App\Lib\System\App;


class baseDomain 
{
     
    public function error($mess)
    {
        \App::get()->Input->error($mess);
    }
    
    
    private $key_;
    
    public function setkey($key)
    {
        $this->key_ = $key;
    }
    
    public function getKey()
    {
        return $this->key_;
    }
    
    
    public function page_array($list,$page)
    {
        $res = [];
        
        $i = 0;
        
        $begin = ($page['number']-1)*$page['size'];
        $end   = $page['number']*$page['size'];
        
        //echo $begin."$".$end."$";
        
        foreach ($list as $v)
        {
            if($end > $i && $i >= $begin)
            {
                $res[] = $v;
            }
            
            if($i >= $end)
                break;
            
            $i++;
        }
        
        
        return $res;
    }
    
    

      
    /*
    private $model_;
    public function setModel($name,$fun)
    {
        $this->model_->$name = $fun;
    }
    
    
    public function __get($name)
    {
        
      $fun =  $this->model_->$name;
      $value = $fun();
               
      $this->name = $value;
        
      return $value;
    }*/
    
   

}
