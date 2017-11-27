<?php

namespace App\Models;

use Phalcon\Mvc\Model;
use App\Bll\API;
use Phalcon\Mvc\Model\Resultset\Simple as Resultset;
use App\Domain\usersDomain;


class reportedModel extends baseModel
{
  
    public function getSource()
    {
        return "reported";
    }
   
    public static function add($uid, $duid,$content)
    {
        $f = new reportedModel();
        
        $f->uid = $uid;
        
        $f->duid = $duid;
        
        $f->content = $content;
        
        $f->add_time = time();
        
        $f->save();
    }
    
    
    public static function get_list_web($page)
    {
        $list =  self::page_pc($page,"select * from reported order by id desc");
    
    
        $list['list'] = usersDomain::format_simple_user_list($list['list']);
    
    
        foreach ($list['list'] as $k=>$v)
        {
            //$list['list'][$k]->img = \config::app['img_bucket_url'].$list['list'][$k]->img;
    
    
            $list['list'][$k]->add_time =  date('Y-m-d H:i:s', $list['list'][$k]->add_time);
            
            
            $user = new usersDomain($list['list'][$k]->duid);
            
            $user_info = $user->simple_info();
            
            if($user_info == true)
            {
                $list['list'][$k]->dnickname = $user_info->nickname;
                
                $list['list'][$k]->dimg = \config::app['img_bucket_url'].$user_info->img;
            }
            else 
            {
                $list['list'][$k]->dnickname = "已删除";
                $list['list'][$k]->dimg = "";
            }
            
            
            
            
    
        }
        
        return $list;
    }
    
    
}
