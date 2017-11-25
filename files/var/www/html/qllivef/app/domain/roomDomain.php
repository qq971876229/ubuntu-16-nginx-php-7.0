<?php

namespace App\Domain;
use App\Models\usersModel;
use App\Models\realNameModel;
use App\RModels\user_simple_RModel;
use App\RModels\user_extend_RModel;
use App\Models\xpUserModel;
use App\Models\rankUserModel;
use App\Models\moneyModel;
use App\Models\uubeanModel;
use App\Models\logGiftModel;
use App\Lib\System\App;
use App\Models\linkModel;
use App\MModels\roomMModel;
use App\Lib\System\Transaction;






class roomDomain  extends baseDomain
{
    
    private function get_user()
    {
        $uid = $this->getKey();
        $user = new usersDomain($this->getKey());
        return $user;
    }
    
    
   public function __construct($uid)
   {
       $this->setkey($uid);
   }
   
   
   
   public function send_room_mess($room_id,$content)
   {
       App::TxMess()->room_mess($this->getKey(),$room_id, $content);
       
   }
   
   public function get_room_user($uid,$page)
   {
       $room = roomMModel::get_room($uid);
       
       if($room == false)
       {
           App::Input()->error("房间不存在");
       }
       
       
       $list =  roomMModel::get_room_user($room->id,$page);
       
       $user_list = array();
       
       foreach ($list as $v)
       {
           
           $row = new \stdClass();
           
           $row->sort = $v->sort;
           $row->uid = $v->uid;
           
           $user_list[] = $row;
       }
       

       
      $user_list =  usersDomain::format_simple_user_list($user_list,"uid");
      
      return ["count"=>$room->watch_count,"list"=>$user_list];
   }
   
   
   public function logout($rid)
   {
       $room = roomMModel::get_room($rid);
       
       if($room == false)
           return;
       
       roomMModel::logout($this->getKey(),$room);
          
   }
   
   
   
   public function login_pass($rid,$pass)
   {
       
       $room = roomMModel::get_room($rid);
        
       if($room == false)
       {
           App::Input()->error("房间不存在");
       }
       
       if($room->is_close == 1)
       {
           App::Input()->out("房间已关闭");
       }
        
       if( $room->pass == null)
       {
           App::Input()->error("不是私密房间");
       }
       
       if($room->pass != md5($pass))
       {
           App::Input()->error("密码不正确");
       }
       
       return $this->get_login_info($room);
       
       
   }
   
   
   
   
   
   public function login_bean($l_uid)
   {
       
       $room = roomMModel::get_room($l_uid);
        
       if($room == false)
       {
           App::Input()->error("房间不存在");
       }
       
       if($room->is_close == 1)
       {
            App::Input()->out("房间已关闭");
       }
       
       
       if( $room->bean == null)
       {
           App::Input()->error("不是收费房间");
       }
       
       if( roomMModel::is_login_bean($this->getKey(), $room) == true)
       {
           return $this->get_login_info($room);
       }
       
       


                   
       $transaction = new Transaction();
       
       $uid = $this->getKey();
       $live_uid = $l_uid;
       $bean = $room->bean;
       
       $transaction->set_commit(function () use($uid,$live_uid,$bean)
       {
           moneyModel::update_cache($live_uid, $bean);
           uubeanModel::update_cache($uid, -$bean);
       });
       
       
       $remark =$room->nickname."[id:".$room->uid."]房间门票";
       
       $transaction->run(function()use($uid,$live_uid,$bean,$remark,$room)
       {
           
           $time = time();
           $money_record = moneyModel::insert($live_uid, $bean,
               $remark,$time,\configPay::bean_type['room']);
       
       
           $uuean_record = uubeanModel::insert($uid,-$bean, $remark, $money_record->id,\configPay::money_type['room']);
       
           roomMModel::login_bean($uid, $room);
           
           return true;
            
       }, "pay:".$uid);
       
       
       
      
       return $this->get_login_info($room);
   }
   
   
   public function login($rid)
   {
       $room = roomMModel::get_room($rid);
       
       if($room == false)
       {
           App::Input()->error("房间不存在");
       }
       
       if($room->is_close == 1)
       {
           App::Input()->out("房间已关闭");
       }
       

             
       return $this->get_login_info($room);
   }
   
   private  function get_login_info($room)
   {
       $room_user_list  = roomMModel::login($this->getKey(), $room);
       
       $user_list = array();
       
       foreach ($room_user_list as $v)
       {
           
           $row = new \stdClass();
            
           $row->sort = $v->sort;
           $row->uid = $v->uid;
            
           $user_list[] = $row;
       }
     
       //------
       $user_list = usersDomain::format_simple_user_list($user_list,"uid");
    
       $user_list_info = ['count'=>$room->watch_count,'list'=>$user_list];   
       //-----------
       
       $live_user = new usersDomain($room->uid);
       
       
       $user = $this->get_user();
       $is_follow = $user->is_follow($room->uid);
       $live_info = $live_user->simple_info();
       $live_info->is_follow = $is_follow;
       $live_info->title = $room->title;
       $live_info->get_uubean = logGiftModel::get($room->uid);
       
       $res = ['live'=>$live_info,'user'=>$user_list_info];
       
       
   
       return $res;
   }
   
   
    public function heartbeat()
    {
        roomMModel::heartbeat($this->getKey());
    }
    
    
    
    
    
    private function ceatePushURLTxSecret($streaid,$txTime)
    {
        
       
        
        $key =  \config::app["live_push_url_key"];
        $md5_val = md5($key . $streaid . $txTime);
        return $md5_val;
    }
    
    
    private function createTxTime($now_time)
    {
        //	$now_time = time();
        $now_time += 3*60*60;
        return dechex($now_time);
    }
    
    
    
   private function create_live_push()
   {
       
       $userid = $this->getKey();
       
       $now_time = time();
       $txTime = $this->createTxTime($now_time);
       
      
       $bizid= \config::app["live_bizid"];
       $tmp_id = str_replace(array("@","#","-"),"_",$userid);
       $live_code = $bizid . "_" . $tmp_id ;
       
        
       $safe_url = "&txSecret=" . $this->ceatePushURLTxSecret($live_code,$txTime) ."&txTime=" .$txTime;
       
        
       $push_url = "rtmp://" . $bizid . ".livepush2.myqcloud.com/live/" .  $live_code . "?bizid=" . $bizid . "&record_interval=10800&record=flv|hls" .$safe_url;
       $hls_play_url = "http://" . $bizid . ".liveplay.myqcloud.com/live/" .  $live_code;
       
       
       $data = new \stdClass();
       $data->push_url = $push_url;
       $data->hls_play_url = $hls_play_url;
       
       
       return $data;
   }
    
    
   
   public function create($data,$activity)
   {
       $user = $this->get_user();
       
       if($user == false)
       {
           App::Input()->error("用户信息不存在");
       }
       
       
       if( count($activity) >1)
       {
           App::Input()->error("不能同时设置密码和门票");
       }
       
       
       
       $data['pass'] = null;
       $data['bean'] = null;
       
       if( isset($activity['pass']) )
       {
           $data['pass'] = md5($activity['pass']);
       }
       
       if( isset($activity['bean']) )
       {
           $data['bean'] = $activity['bean'];
       }
       
       
       $simple_rm = $user->simple_info();
       
       
        $data['uid'] =     $simple_rm->uid;
        $data['nickname'] =     $simple_rm->nickname;
        $data['img'] =     $simple_rm->img;
        $data['rank_user'] =     $simple_rm->rank_user;
        $data['real_name'] =     $simple_rm->real_name;
        $data['verified_reason'] =     $simple_rm->verified_reason;
        $data['sex'] =     $simple_rm->sex;
        
        $push_url = $this->create_live_push();
        
        $data['push_url'] = $push_url->push_url;
        $data['flow_addr'] = $push_url->hls_play_url;
        
      return  roomMModel::create_room($simple_rm->uid,$data);

       
   }
   
   
   public function get_follow_list($page)
   {
       $room_list = roomMModel::get_hot_list($page);
       
       $user = $this->get_user();
       
       $follow_list = $user->get_follow();
       
     
       $res_list = [];
       
       foreach ($room_list as $k=>$v)
       {
             if( in_array($v->uid, $follow_list))
             {
                 $user = new usersDomain($v->uid);
                 $user = $user->simple_info();
                 
                 $user->watch_count = $v->watch_count;
                 $user->place_name = $v->place_name;
                 $user->lat = $v->lat;
                 $user->lng = $v->lng;
                 $user->title = $v->title;
                 $user->is_close = $v->is_close;
                 
                 
                 
                 $res_list[] = $user;
              
             }
       };
       
       
       foreach ($follow_list as $k=>$v)
       {
           
           foreach ($room_list as $x=>$y)
           {
               if($v == $y->uid)
               {
                   unset($follow_list[$k]);
               }
           }
           
       }
       
       
       $follow_list = usersDomain::format_simple_user_list($follow_list,null);
       
       foreach ($follow_list as $k=>$v)
       {
            $follow_list[$k]->watch_count = 0;
            $follow_list[$k]->place_name = "";
            $follow_list[$k]->lat = "";
            $follow_list[$k]->lng = "";
            $follow_list[$k]->title = "";
            $follow_list[$k]->is_close = 1;
       }
       

       
       
       return   array_merge($res_list, $follow_list);

   }
   
   
   
  
   
   public function close_room($uid)
   {
       $room = roomMModel::get_room($uid);
       
       $info = ["time"=>0,"user_total"=>0,"bean_total"=>0];
       
       if ($room != false)
       {
           $info["time"] = time()- $room->add_time;
           $info["user_total"] = $room->user_total;
           $info["bean_total"] = $room->bean_total;
           
           roomMModel::close_room($room);
       }
       
       return $info;
   }
  
}
