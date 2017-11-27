<?php
namespace bcl\redis;

use bcl\exception\bclException;
use bcl;

class accountsBase extends base
{
 
   

    public function commit()
    {
        
    }
    
       
    public  function query_value()
    {
        
    }
    
    public function get_value()
    {
       $value =  numbase::get();
       
       if($value != false)
           return $value;
       
           
       $value = $this->queryValue();
       
       $this->set($value);
       
       return $value;
    }
    
    
    
   
}