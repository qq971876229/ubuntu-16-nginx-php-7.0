<?php
namespace App\Lib;
use App\Models\usersModel;
use App\Lib\img\ImageV2;
use App\Bll\Auth;
use App\Lib\System\App;
use App\Models\vodSessionModel;


class auth_login
{
    
    
    private  function get($url)
    {
        
        ///初始化
     $curl = curl_init();
    //设置抓取的url
     curl_setopt($curl, CURLOPT_URL, $url);
    //设置头文件的信息作为数据流输出
    curl_setopt($curl, CURLOPT_HEADER, 0);
    //设置获取的信息以文件流的形式返回，而不是直接输出。
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    
    
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    //执行命令
    $data = curl_exec($curl);
    
    
    if($data == false)
    {
        App::Input()->error("curl:".curl_error($curl));
    }
    //关闭URL请求
    curl_close($curl);
        

       return json_decode($data);
    //显示获得的数据
        
    }
    
    
    
    
    
    /*
     *@通过curl方式获取制定的图片到本地
     *@ 完整的图片地址
     *@ 要存储的文件名
     */
    function getImage($url = "", $filename = "") {
        if(is_dir(basename($filename))) {
            echo "The Dir was not exits";
            Return false;
        }
        //去除URL连接上面可能的引号
        $url = preg_replace( '/(?:^[\'"]+|[\'"\/]+$)/', '', $url );
        $hander = curl_init();
        $fp = fopen($filename,'wb');
        curl_setopt($hander,CURLOPT_URL,$url);
        curl_setopt($hander,CURLOPT_FILE,$fp);
        curl_setopt($hander,CURLOPT_HEADER,0);
        curl_setopt($hander,CURLOPT_FOLLOWLOCATION,1);
        //curl_setopt($hander,CURLOPT_RETURNTRANSFER,false);//以数据流的方式返回数据,当为false是直接显示出来
        curl_setopt($hander,CURLOPT_TIMEOUT,60);
        /*$options = array(
         CURLOPT_URL=> 'http://jb51.net/content/uploadfile/201106/thum-f3ccdd27d2000e3f9255a7e3e2c4880020110622095243.jpg',
         CURLOPT_FILE => $fp,
         CURLOPT_HEADER => 0,
         CURLOPT_FOLLOWLOCATION => 1,
         CURLOPT_TIMEOUT => 60
         );
        curl_setopt_array($hander, $options);
        */
        curl_exec($hander);
        curl_close($hander);
        fclose($fp);
        Return true;
    }
    
    
    public function upload_img($url)
    {
        
        $file_name = time().rand(100000,999999);
        $path = sys_get_temp_dir()."/".$file_name;
        
        
        
        $this->getImage($url,$path);
        

                
        $file_name = "userhead/".$file_name;
        
        //$info = ImageV2::upload($path,$file_name);
        
        $info = App::Img()->upload_img_v4($path,$file_name);
        
        
        return $info;
        

        
        
    }
    
    public function  get_wb_user_info($access_token,$wb_uid)
    {
        $url = "https://api.weibo.com/2/users/show.json?access_token=".$access_token."&uid=".$wb_uid;
        
        $info = $this->get($url);
        
        if(isset($info->error))
        {
            App::Input()->error($info->error);
        }
        
        return $info;
        
    }
    
    
    public function  get_wx_user_info($access_token,$openid)
    {
    
        $url = "https://api.weixin.qq.com/sns/userinfo?access_token=$access_token&openid=$openid";
        
        $info = $this->get($url);
        
        
        if(isset($info->errcode))
        {
            App::Input()->error($info->errmsg);
        }
        
        return $info;
    
    }
    
    public function  get_qq_user_info($access_token,$openid)
    {
    
      
        
     
        
        $appid=\config::app['qq_app_id'];
        $key =\config::app['qq_app_key'];
        
        
        //$appid="1105649706";
        //$key = "08VRqh0qGZPma5xJ&";
        
        
        
         $url =  "appid=$appid&format=json&openid=$openid&openkey=$access_token&pf=qzone";
        
        $url = urlencode($url);
 
        $url = "GET&%2Fv3%2Fuser%2Fget_info&".$url;       
        
        
        //echo "$".$url."$";
        

      
    
        
        $key = hash_hmac('sha1',$url, $key,TRUE);
        
      
       $key =  base64_encode($key);
       $key =  urlencode($key);
       
       
      // echo "$".$key."$";
       

 
             
       $url = "http://openapi.sparta.html5.qq.com/v3/user/get_info?openid=$openid&openkey=$access_token&appid=$appid&pf=qzone&format=json&sig=$key";
       
       
      // echo $url;
      // die();
       
        $info = $this->get($url);
        
        if($info->ret !=0)
        {
            App::Input()->error($info);
            App::Input()->error($info->msg);
        }
    
        
        return $info;
    
    }
    
    
    public function login_wb($access_token,$wb_uid)
    {
     
     
        $info = $this->get_wb_user_info($access_token,$wb_uid);
           
        $uid = usersModel::login_wb2uid($wb_uid);
        
        if($uid == false)
        {

            $sex = 0;
            if($info->gender == "m")
            {
                $sex = 1;
            }
            elseif ($info->gender == "f")
            {
                $sex = 2;
            }
            elseif ($info->gender == "n")
            {
                $sex = 0;
            }
            
            
            
            $img = $this->upload_img($info->avatar_hd);
            $nickname =  $info->screen_name;
            
            $uid = usersModel::create_login_wb_user($wb_uid, $nickname, $img,$sex);
            
        }


        return App::Auth()->auth($uid);
        
    }
    
    
    public function bind_wx($access_token,$openid)
    {
        $info = $this->get_wx_user_info($access_token,$openid);
        
        $uid = usersModel::login_wx2uid($openid);
        
        if($uid == true)
        {
            App::Input()->error("账号已经使用");
        }
        
        
    }
    
    public function bind_wb($uid,$access_token,$wb_uid)
    {
        $info = $this->get_wb_user_info($access_token, $wb_uid);
    
        if($info == false)
        {
            App::Input()->error("获取用户信息错误");
        }
        
        usersModel::bind_wb_user($uid, $wb_uid);
    }
    
    
    public function bind_qq($uid,$access_token,$open_id)
    {
        $info = $this->get_qq_user_info($access_token, $open_id);
    
        if($info == false)
        {
            App::Input()->error("获取用户信息错误");
        }
    
        usersModel::bind_qq_user($uid, $open_id);
    }
    
    public function login_wx($access_token,$openid,$reference_id)
    {
        
        $info = $this->get_wx_user_info($access_token,$openid);
        
        
        $uid = usersModel::login_wx2uid($openid);
        
        
        if($uid == false)
        {
            
            $img = "";
             if(strlen($info->headimgurl) > 2)
             {
                 $img = $this->upload_img($info->headimgurl);
             }


            $http_host = $_SERVER['HTTP_HOST'];

            if ($http_host == 'testapi.miyintech.com') {
                App::Input()->error("测试服务器，请重新下载正式服应用");
            } else {
//                $uid = usersModel::create_login_moblie_user($moblie,$reference);
                $uid = usersModel::create_login_wx_user($openid,$info->nickname, $img,$info->sex,$reference_id);
            }


        }

        //set the user free
//        vodSessionModel::DB()->getOne("UPDATE vod_session SET state=2 WHERE view_id=".$uid);
        
        return App::Auth()->auth($uid);
        
        
    }
    
    
    public function login_qq($access_token,$openid)
    {
         
        $info = $this->get_qq_user_info($access_token, $openid);
       
       
        $uid = usersModel::login_qq2uid($openid);
        

        
        if($uid == false)
        {
            
            $login_qq  = $openid;
           
            $sex = 0;
            
            if($info->gender == "男")
            {
                $sex = 1;
            }
            elseif ($info->gender == "女")
            {
                $sex = 2;
            }


         
          $img = $this->upload_img($info->figureurl);
          

            $nickname =  $info->nickname;
            
            $uid = usersModel::create_login_qq_user($login_qq, $nickname, $img,$sex);
            
        }
    
        return App::Auth()->auth($uid);
    
    }
    
    
}


