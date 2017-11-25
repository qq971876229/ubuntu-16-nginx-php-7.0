<?php

namespace App\Controllers;

use App\Lib\System\App;
use App\Models\userVideoModel;


class ImgController extends baseController
{

    public function test_imgAction()
    {
        App::Img()->upload_v4("");
    }


    public function upload_zipAction()
    {
        App::Input()->out(App::Img()->upload_zip());
    }

    public function uploadAction()
    {
        App::Input()->out(App::Img()->upload_one("img"));
    }

    public function upload_fileAction()
    {
        App::Input()->out(App::Img()->upload_one("file"));
    }

    public function upload_videoAction()
    {

        App::Input()->out(App::Img()->upload_video("video"));

    }

    /**
     * add the video url and the video's print screen to the user_video table
     * uid
     * video //the tencent video url
     * img // the tencent video print screen url
     */
    public function add_video_imgAction()
    {

        $uid = App::Auth()->authLogin();
        $video = App::Input()->get("video");
        $img = App::Input()->get("img");

        userVideoModel::add($uid, $video, $img);

        App::Input()->out("ok");


    }


}
