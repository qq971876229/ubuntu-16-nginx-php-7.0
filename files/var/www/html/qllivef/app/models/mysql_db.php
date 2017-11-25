<?php

namespace App\Models;
use Phalcon\Db;


class mysql_db
{
   
    private $conn_;
    function __construct() 
    {
                
        $config = \config::database;
        

        $conn = new  \Phalcon\Db\Adapter\Pdo\Mysql(array(
                    'host' => $config['host'],
                    'username' => $config['username'],
                    'password' => $config['password'],
                    'dbname' => $config['dbname'],
                    'charset' => $config['charset'],
                    'collation' => $config['collation']
                ));
        
        $this->conn_ = $conn;
         
    }
    
    
    function  get_conn()
    {
        return $this->conn_;
    }
    
    
    function begin()
    {
        $this->conn_->begin();  
    }
    
    function commit()
    {
        $this->conn_->commit();
    }
    
    function rollback()
    {
        $this->conn_->rollback();
    }
    
    
    function  getOne($sql,$argv=null)
    {
         $result =  $this->conn_->query($sql,$argv);
        
        $result->setFetchMode(Db::FETCH_NUM);
              
        while ($v = $result->fetch()) 
         {
                return  $v[0];
          }
    }

    /**
     * just query the sql ,don't return anything
     * @param $sql
     * @param null $argv
     * @return bool
     */
    function  query_sql($sql,$argv=null)
    {
        $result =  $this->conn_->query($sql,$argv);

        return true;
    }

    
    function getAll($sql,$argv=NULL)
    {

        $result =  $this->conn_->query($sql,$argv);
        
        $result->setFetchMode(Db::FETCH_OBJ);
         
        return $result->fetchAll();
       
    }
    

    
}
