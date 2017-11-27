<?php
namespace App\Lib\System;

class Input
{
    


    private  $transaction_;
    
    public  function setTransaction($transaction)
    {
        $this->transaction_ = $transaction;
    }
    
    
    
    
    private  $json_data_;
    
  
    public  function get_page()
    {
        return self::get("page");
    }
    
    
    private  function inject_check($Sql_Str)
    {
    
        $check=preg_match('/select|insert|update|delete|\'|\\*|\*|\.\.\/|\.\/|union|into|load_file|outfile/i',$Sql_Str);
        if ($check) 
        {
            $this->error("关键词错误");
        }
    }
    
    
    private  function load() 
    {

        if(empty($this->json_data_))
        {
            $data = file_get_contents("php://input");
            $this->inject_check($data);
            $this->json_data_ = json_decode($data,true);
        }
        
        
    }
    
    public function dump($info)
    {
        echo '<pre>';
        print_r($info);
        echo '</pre>';
        die();
    }


    public function get($key, $type = 0)
    {

        $this->load();

        if (isset($this->json_data_[$key])) {
            return $this->json_data_[$key];
        } else {

            if ($type === 0) {

                return 0;
            } else {

                $this->error($key."参数必填");
            }
        }


    }
    
    
    public  function get_array($key)
    {
        $this->load();
        
        $data = array();
        
        foreach ($key as $v)
        {
            
            if(isset($this->json_data_[$v]))
            {
                $data[$v] = $this->json_data_[$v];
            }
            else 
            {
                $this->error($v."参数必填");
            }
           
        }
        
        return $data;
    }
    
    
    public  function get_null($key,$def = null)
    {
        
        $this->load();
        
        if(isset($this->json_data_[$key]))
        {
            return $this->json_data_[$key];
        }
        else
        {
             
            return $def;
        }
        
    }
    
    public  function get_array_null($key)
    {
        
        $this->load();
        
        $data = array();
        
        foreach ($key as $v)
        {
        
            if(isset($this->json_data_[$v]))
            {
                $data[$v] = $this->json_data_[$v];
            }  
        }
        
        return $data;
    }
    

    public  function out($desc)
    {
        
        if($this->transaction_ !=null)
        {
            $this->transaction_->commit();
        }
        
        
        $o = array();
        $status = array();
        $status['succeed'] = 1;
        $o['status'] = $status;
        $o['data'] = $desc;
        die(json_encode($o,JSON_UNESCAPED_UNICODE));
    }
    
    public  function error($desc,$code="-1")
    {
        
        
        if($this->transaction_ !=null)
        {
           
            $this->transaction_->rollback();
        }
        
        
        $o = array();
        $status = array();
        $status['succeed'] = 0;
        $status['error_code'] = $code;
        $status['error_desc'] = $desc;
        $o['status'] = $status;
        die(json_encode($o,JSON_UNESCAPED_UNICODE));
        ;
    }
    
}