<?php

namespace App\Lib\System;

use qcloudcos\Cosapi;

class Img
{

    public function upload($path, $file_name)
    {

        require(dirname(__FILE__).'/../cos4/include.php');

        $bucket = 'liveimg';


        Cosapi::setTimeout(180);
        Cosapi::setRegion('sh');


        $info = Cosapi::upload($bucket, $path, $file_name);


        if ($info['code'] == 0) {
            return $file_name;
        } else {
            App::Input()->error("图片上传错误:".$info['message']);
        }


    }


    public function upload_img_v4($path, $file_name)
    {
        require(dirname(__FILE__).'/../cos4/include.php');
        Cosapi::setTimeout(180);
        Cosapi::setRegion(\config::app['img_region']);

        $bucket = \config::app['img_bucket'];


        $info = Cosapi::upload($bucket, $path, $file_name);


        if ($info['code'] == 0) {
            return $file_name;
        } else {
            App::Input()->error("图片上传错误:".$info['message']);
        }


    }


    public function upload_v4($path)
    {

        require(dirname(__FILE__).'/../cos4/include.php');

        $bucket = \config::app['img_bucket'];

        if (empty($_FILES['img']['tmp_name'])) {
            App::Input()->error("没有图片");

            return;
        }


        $dst = $path."/".$this->random_filename();

        Cosapi::setTimeout(180);
        Cosapi::setRegion(\config::app['img_region']);

        $src = $_FILES['img']['tmp_name'];

        $info = Cosapi::upload($bucket, $src, $dst);


        if ($info['code'] == 0) {
            return $dst;
        } else {
            App::Input()->error("图片上传错误:".$info['message']);
        }


    }


    public function upload_one($path)
    {

        return $this->upload_v4($path);

    }

    public function upload_video($path)
    {

        require(dirname(__FILE__).'/../cos4/include.php');

        $bucket = \config::app['img_bucket'];


        if (empty($_FILES['video']['tmp_name'])) {
            App::Input()->error("没有视频文件");

            return;
        }

        $src = $_FILES['video']['tmp_name'];
        $ext = explode('.',strrev($_FILES['video']['name']));
        $ext = strrev($ext[0]);

        $dst = $path."/".$this->random_filename().".".$ext;

        Cosapi::setTimeout(180);
        Cosapi::setRegion(\config::app['img_region']);


        $info = Cosapi::upload($bucket, $src, $dst);


        if ($info['code'] == 0) {
            return $dst;
        } else {
            App::Input()->error("视频上传错误:".$info['message']);
        }


    }


    private function random_filename()
    {
        $str = '';
        for ($i = 0; $i < 9; $i++) {
            $str .= mt_rand(0, 9);
        }

        return time().$str;
    }


}