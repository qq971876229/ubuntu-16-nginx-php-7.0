<?php

namespace App\Models;

use App\Lib\System\App;


class userVideoModel extends baseModel
{

    public function getSource()
    {
        return "user_video";
    }


    public static function add($uid, $vod, $img)
    {
        $user_video = new userVideoModel();

        $user_video->uid = $uid;
        $user_video->video = $vod;
        $user_video->img = $img;

        $user_video->save();
    }

    /**
     * stick the video to the top
     * @param $uid
     * @param $video_id
     */
    public static function stick($uid, $video_id)
    {
        $video = userVideoModel::findFirst("id=".$video_id);

//        if ($video->stick == 1) {
//
//            $video->stick = 0;
//            $video->stick_time = 0;
//        } else {

            $video->stick = 1;
            $video->stick_time = time();
//        }

        $video->save();


    }


    public static function del($uid, $id)
    {
        $video = userVideoModel::findFirst("id='$id' and uid='$uid'");

        if ($video == false) {
            App::Input()->error("没有视频");
        }

        $video->delete();

    }

    public static function get_list($uid)
    {

        $video = userVideoModel::find("uid='$uid'");

        if ($video == null) {
            return array();
        }

        return $video;
    }
}
