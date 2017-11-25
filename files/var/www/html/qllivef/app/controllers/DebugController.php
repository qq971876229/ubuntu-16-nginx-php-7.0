<?php
namespace App\Controllers;
use App\Models\usersModel;
use App\Lib\System\App;
use App\Domain\payDomain;
use App\Domain\usersDomain;
use App\Models\logPayModel;





class DebugController extends baseController
{
   
    public function InterfaceAction()
	{ 
	   $data = file_get_contents("php://input");
	   echo $data;
	} 
	
	public function loginAction()
	{
	    $user_name = App::Input()->get("user_name");
        $pass = App::Input()->get("pass");
    
        $uid =  usersModel::login_name2uid($user_name);  
               
        usersModel::check_pass($uid,$pass);
        
        $data = App::Auth()->auth($uid);
        
        App::Input()->out($data);
        
	
	}
	
	public function registerAction()
	{
	    //--
	    $user = App::Input()->get("user_name");
	    $pass = App::Input()->get("pass");
	    
	     $id = usersModel::create_login_name_user($user);
	    usersModel::edit_pass($id,$pass);
	    App::Input()->out("ok");
	}
	
	public function add_moneyAction()
	{
	     App::Auth()->authLogin();
	    
	    $uid = App::Input()->get("uid");
	    $money = App::Input()->get("money");
	    
	    $user = new usersDomain($uid);
	    
	    if($user->simple_info() == false)
	    {
	        App::Input()->error("不存在的用户");
	    }
	    
	    payDomain::pay_debug_add_money($uid, $money);
	    App::Input()->out("ok");
	}
	
	
	public function live_mgrAction()
	{
	
	    $time = time() + 60;
	
	    $sign = MD5("ca61bfd219f407d19735b29b2a39819e".$time);
	    
	    $uid = App::Input()->get("uid");
	    $state = App::Input()->get("state");
	
	    if($state == 0)
	    {
	        $act = "forbid";
	    }
	    else if($state == 1)
	    {
	        $act = "resume";
	    }
	
	
	     
	    $url = "http://fcgi.video.qcloud.com/common_access?appid=1252500699&interface=Live_Channel_SetStatus&t=$time&sign=$sign";
	
	    $view_url = $url."&Param.s.actio=$state&Param.s.channel_id=4555_$uid&Param.n.abstime_end=1000";
	
	  
	    // echo $url."\r\n";
	
	    //初始化
	    $ch = curl_init();
	    curl_setopt($ch, CURLOPT_URL, $view_url);
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	    curl_setopt($ch, CURLOPT_HEADER, 0);
	    $output = curl_exec($ch);
	
	
	
	    curl_close($ch);
	    
	    App::Input()->out("ok");
	
	     
	}
	
	
	public function messAction()
	{
	    //echo "11";
	    

	   // $this->view->setTemplateAfter('aa');
	    App::Auth()->authLogin();
	    $mess = App::Input()->get("content");
	    $uid = App::Input()->get("uid");
	    
	    App::TxMess()->system_mess($uid,$mess);
	  
	    
	    App::Input()->out("ok");
	    
	     
	}
	
	
	public function all_messAction()
	{
	    
	    App::Auth()->authLogin();
	    $mess = App::Input()->get("content");
	   
	    App::TxMess()->system_mess(0,$mess);
	    
	    App::Input()->out("ok");
	    
	}
	
	public function test_payAction()
	{
	    App::Auth()->authLogin();
	    
	    $order = App::Input()->get("order");
	    $amount = App::Input()->get("amount");
	    
	    $info = logPayModel::paid($order,$amount);
	    
	    App::Input()->out($info);
	}
	
}

