<?php

namespace App\Models;

use Phalcon\Mvc\Model;
use App\Bll\API;
use Phalcon\Mvc\Model\Resultset\Simple as Resultset;
use App\Domain\usersDomain;


class feedbackModel extends baseModel
{
  
    public function getSource()
    {
        return "feedback";
    }
   
    public static function add($uid, $content)
    {
        $f = new feedbackModel();
        
        $f->uid = $uid;
        
        $f->content = $content;
        
        $f->add_time = time();
        
        $f->save();
    }
    
    public static function get_list_web($page)
    {
        $list =  self::page_pc($page,"select * from feedback order by id desc");
        
        
        $list['list'] = usersDomain::format_simple_user_list($list['list'],'uid',1);
        
        
        foreach ($list['list'] as $k=>$v)
        {
            $list['list'][$k]->img = \config::app['img_bucket_url'].$list['list'][$k]->img;
            
            
            $list['list'][$k]->add_time =  date('Y-m-d H:i:s', $list['list'][$k]->add_time);
        
        }
        
        
        
        return $list;
        
    }
  
  
}
