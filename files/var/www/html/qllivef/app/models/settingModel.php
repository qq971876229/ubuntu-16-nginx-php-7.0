<?php
/**
 * Created by PhpStorm.
 * User: wxx
 * Date: 2017/9/10
 * Time: 下午9:49
 */

namespace App\Models;

use bcl\redis\cacheBase;

class settingModel extends baseModel
{

    public function getSource()
    {
        return 'ql_setting';
    }


    /**
     * @param $id
     */
    /**
     * get the setting from cache,if don't exist in cache,get from database
     * @param $id
     * @return bool|mixed|string
     */
    public static function get($id){

        $key = "setting_".$id;
        $setting = cacheBase::redis_get($key);

        if($setting!=false){
            return $setting;
        }else{
            $setting = settingModel::findFirst("id=$id");
            if($setting){
                cacheBase::redis_set($key,$setting);
                return $setting;
            }else{
                return false;
            }

        }

    }




}