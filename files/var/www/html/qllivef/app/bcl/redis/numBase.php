<?php
namespace bcl\redis;

use bcl\exception\bclException;
use bcl;

class numBase extends base
{
 
   
    private $key_;
    
    public function __construct($key)
    {
       $this->key_ = $key;
    }
    
    
    public function  exists()
    {
        $rd = self::get_rd();
        
        return $rd->exists($this->key_);
    }
    

       
    public function add($num)
    {
        $rd = self::get_rd();
        $rd->incrBy($this->key_,$num);
    }
    
    public function sub($num)
    {
        $rd = self::get_rd();
        
        $rd->decrBy($this->key_,$num);
    }
    
    public function get()
    {
        $rd = self::get_rd();
        $num = $rd->get($this->key_);

        if($num == 0)
            return $num;
        
           
         return $num;   
         
    }
    
    public function set($num,$ttl = \configCache::user["info"])
    {
        $rd = self::get_rd();        
        $rd->set($this->key_,$num,$ttl);
    }
    
   
}