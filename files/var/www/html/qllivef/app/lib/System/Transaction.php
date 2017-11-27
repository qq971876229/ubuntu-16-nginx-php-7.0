<?php
namespace App\Lib\System;

use App\Models\baseModel;
use bcl;
use App\Lib\System\App;

//事务
class Transaction
{
 
    
    private $lock_;
    private function begin($lock_key)
    {
        
        $this->lock_ = new bcl\redis\lockBase($lock_key);
        
        if( $this->lock_->lock() == false)
        {
            App::Input()->error("点击太快");
        }
        
         App::Input()->setTransaction($this);
        
        baseModel::DB()->begin();
    }
    
    
    
    public  function commit()
    {
        
        foreach ($this->unit_list_ as $v)
        {
            $v->commit();
        }
        
        
     
        baseModel::DB()->commit();
           
        $this->lock_->unlock();
        
        App::Input()->setTransaction(null);
    }
    
    public  function rollback()
    {
       
        foreach ($this->unit_list_ as $v)
        {
            $v->rollback();
        }
        
        
        baseModel::DB()->rollback();
        $this->lock_->unlock();
    }
    
    
    

    private  $unit_list_ = array();
    public function set_commit($unit)
    {
       
        $this->unit_list_[]  = $unit;
        
    }
    
    
    private $error_ = "执行失败";
    public function set_error($error)
    {
        $this->error_ = $error;
    }
    
    
    public function run($fun,$lock_key)
    {
  
        $this->begin($lock_key);
        
        try
        { 
            if($fun() == true)
            {
                $this->commit();
                
            }
            else 
            {
                App::Input()->error($this->error_);
            }
            
        }
        catch(\Exception $e)
        { 
            App::Input()->error("Transaction:".$e->getMessage());
        }
                
    }
    
   
}