<?php
namespace bcl\redis;

class base
{


    private static  $host_;
    private static  $port_;
    private static  $pass_;
    private static  $select_;

    private static $rdb_;




    public static function del($key)
    {

        self::get_rd()->del($key);
    }

   public static function set_select_db($num)
   {

       if(self::$rdb_ == null)
           self::get_rd();

       self::$rdb_->select($num);
   }

   public static function error($mess,$code = -1)
   {
       \App::get()->Input->error($mess,$code);
   }

  public   static function get_rd()
   {
       if(self::$rdb_ == null)
       {

           self::$rdb_ = new \Redis();

           $redis = self::$rdb_;

           /*从环境变量里取host,port,user,pwd*/
           $host = self::$host_;
           $port = self::$port_;

           $ret = $redis->connect($host, $port);
           if ($ret === false)
           {
               self::error($redis->getLastError());
           }

           $ret = $redis->auth(self::$pass_);
           if ($ret === false)
           {
               self::error($redis->getLastError());
           }

       }

       self::set_select_db(self::$select_);

       return self::$rdb_;
   }


    public  static  function config($host, $port,$pass,$select)
    {

        self::$host_ = $host;
        self::$port_ = $port;
        self::$pass_ = $pass;
        self::$select_ = $select;

    }

}