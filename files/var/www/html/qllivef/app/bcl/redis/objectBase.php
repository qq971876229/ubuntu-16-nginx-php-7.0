<?php
namespace bcl\redis;

use bcl\exception\bclException;
use bcl;

class objectBase extends base
{
 
    private $list_;
    private $key_;
    private $key_full_;
    private $data_;
    
    
    public function getSource()
    {
        return $this->list_;
    }
    
    function __construct($key,$list = null,$select_db=0)
    {
        $this->key_ = $key;
        $this->list_ = $list;
        $this->key_full_ = $this->getSource().":".$this->key_;
        $this->data_ = new \stdClass();
        self::set_select_db($select_db);
        
     }
     

    public function load()
    {
       
        $rd = self::get_rd();
        
        $data = $rd->get($this->key_full_);
        
       
        if($data == false)
            return false;
        
        $data  = json_decode($data);
      
        unset($data->key_);
        unset($data->key_full_);
        unset($data->data_);
        unset($data->list_);
        
        foreach ($data as $k=>$v)
        {        
            $this->$k = $v;
        }
                
        return true;
    }
    
    public function set($data = null,$ttl = 0)
    {
        
        $rd = self::get_rd();
        
        if($data != null)
        {
            foreach ($data as $k=>$v)
            {                
                $this->$k = $v;
            }
            
        }
        
        //echo $this->key_full_;
   
        if($ttl == 0)
        {
            $rd->set($this->key_full_,json_encode($this));
        }
        else 
        {
            $rd->set($this->key_full_,json_encode($this),$ttl);
        }
     
       
    }
    
    

    
    public  function expire($ttl)
    {
        $rd = self::get_rd();
         
        return  $rd->expire($this->key_full_,$ttl);
    }
      

     
     public function getKey()
     {
         return $this->key_;
     }
     
 
}