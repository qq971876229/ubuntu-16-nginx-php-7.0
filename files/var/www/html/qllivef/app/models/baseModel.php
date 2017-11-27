<?php

namespace App\Models;

use bcl\redis\cacheBase;
use App\Lib\System\App;


class baseModel extends \Phalcon\Mvc\Model
{

    private static $db_;

    public static function DB()
    {
        if (empty(self::$db_)) {
            self::$db_ = new mysql_db();
        }

        return self::$db_;
    }

    public static function error($mess)
    {
        //  App::Input()->error($mess);
        //throw new \Exception($mess);

        App::Input()->error($mess);

    }


    protected static function page_pc($page, $sql, $argv = null)
    {


        $all = self::DB()->getAll($sql, $argv);
        $count = count($all);
        $total = ceil($count / $page['size']);


        $begin = ($page['number'] - 1) * $page['size'];
        $end = $page['size'];
        $sql = $sql." LIMIT $begin,$end";

        $res = array();
        $page_info = array();
        $page_info['total'] = $total;

        //echo $sql;


        $res['list'] = self::DB()->getAll($sql, $argv);
        $res['page'] = $page_info;

        return $res;

    }


    protected static function page($page, $sql, $argv = null)
    {


        /*
         $all = self::DB()->getAll($sql,$argv);
         $count = count($all);
         $total = ceil($count/$page['size']);*/


        $begin = ($page['number'] - 1) * $page['size'];
        $end = $page['size'];
        $sql = $sql." LIMIT $begin,$end";


        //$res = array();
        //$page_info = array();
        //$page_info['total'] = $total;


        //$res['list'] = self::DB()->getAll($sql,$argv);
        //$res['page'] = $page_info;

        return self::DB()->getAll($sql, $argv);

    }


    //执行失败
    public function onvalidationfails()
    {
        $mess = "";
        foreach ($this->getMessages() as $message) {
            $mess = $mess.$message."\n";
        }


        $sapi_type = php_sapi_name();

        if ($sapi_type == 'cli') {
            echo $mess;

            return;
        }


        App::Input()->error($mess);

    }


}
