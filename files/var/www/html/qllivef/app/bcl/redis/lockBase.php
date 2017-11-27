<?php
namespace bcl\redis;

use bcl\exception\bclException;
use bcl;

class lockBase extends base
{
 
   
    public  $key_;
    private $time_;
    
    public function __construct($key)
    {
        
        $this->key_ = "lock:".$key;
        $this->time_ = 10;
    }
    
   
    public  function lock()
    {
                
        $rd = self::get_rd();
        $rd = self::get_rd();
        
        $v = time().rand(0,99999);
    
        if( $rd->setnx($this->key_,$v) == 0)
        {
            
            if( $rd->ttl($this->key_) == -1)
            {
                $rd->expire($this->key_,$this->time_);
            }
            
            return false;
        }
        
        
        $rd->set($this->key_,$v);
        $rd->expire($this->key_,$this->time_);
        
        $rv = $rd->get($this->key_);
        
        //获取锁成功
        if($rv == $v)
        {
            return true;
        }
        else
        {
            return false;
        }
    

    }
    
    public   function unlock()
    {
        $rd = self::get_rd();    
               
        $rd->del($this->key_);

    }
   
}