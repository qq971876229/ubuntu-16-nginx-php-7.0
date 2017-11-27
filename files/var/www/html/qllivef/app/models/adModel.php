<?php

namespace App\Models;

use Phalcon\Mvc\Model;
use Phalcon\Mvc\Model\Resultset\Simple as Resultset;
use App\Bll\Input;
use App\Lib\Geohash;
use App\App;
use App\Cache\keyCache;


class adModel extends baseModel
{


    public function getSource()
    {
        return "ad";
    }


    public static function edit($id, $img, $link, $type, $sort = 0)
    {
        $ad = adModel::findFirst("id=$id");

        $ad->sort = $sort;
        $ad->img = $img;
        $ad->link = $link;
        $ad->type = $type;
        $ad->save();

    }

    public static function random($num)
    {
        return usersModel::DB()->getAll("select * from ad ORDER BY RAND() LIMIT 0,$num");
    }


    public static function list_pc($page)
    {

        $list = usersModel::page_pc($page, "select * from ad order by type,id");

        $ad_list = $list['list'];

        foreach ($ad_list as $k => $v) {
            $ad_list[$k]->img = \config::app['img_bucket_url'].$ad_list[$k]->img;
        }

        $list['list'] = $ad_list;

        return $list;
    }


    public static function get($type)
    {


        $sql = "SELECT * FROM ad WHERE type='$type' ORDER BY sort desc";


        $ad = adModel::DB()->getAll($sql);

        return $ad;


    }

    public static function add($img)
    {
        $ad = new adModel();

        $ad->img = $img;
        $ad->save();
    }


    public static function get_id($id)
    {
        $ad = adModel::findFirst("id=$id");

        return $ad;
    }

    public static function del($id)
    {

        $ad = adModel::findFirst("id=$id");

        $ad->delete();
    }

    public static function add_ad($img, $link, $type, $sort = 0)
    {

        $ad = new adModel();

        $ad->img = $img;
        $ad->link = $link;
        $ad->type = $type;
        $ad->sort = $sort;

        $ad->save();
    }

}
