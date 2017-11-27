<?php

namespace App\Models;
use App\Lib\System\App;




class userImgModel extends baseModel
{
  
    public function getSource()
    {
        return "user_img";
    }
   
    
    public static  function add($uid,$img)
    {
        $user_img = new userImgModel();
        
       $user_img->uid = $uid;
       $user_img->img = $img;
       
       $user_img->save();
    }
    
    public  static  function del($uid,$id)
    {
        $img = userImgModel::findFirst("id='$id' and uid='$uid'");
        
        if($img == false)
        {
            App::Input()->error("没有图片");
        }
        
        $img->delete();
       
    }
  
    public  static  function get_list($uid)
    {

        $img = userImgModel::find("uid='$uid'");

//        $video = userVideoModel::find("uid='$uid'");

        $video = userVideoModel::find(array(
            'conditions'=>'uid=:uid:' ,
            "bind"=>array ('uid' =>$uid),
            'order' => 'stick desc,stick_time desc'
        ));



//        $list = $video->toArray()+$img->toArray();

        $list = array_merge($video->toArray(),$img->toArray());


        
    
       if($img == null)
           return array();
        
        return $list;
    }
}
