<?php
namespace App\Controllers\mgr;

use Phalcon\Mvc\Controller;

class baseController extends Controller
{

    public function app_name()
    {

        $http_host = $_SERVER['HTTP_HOST'];

        if ($http_host == 'testapi.miyintech.com') {

            $app_name = '测试服';
        } elseif ($http_host == 'qlapi.miyintech.com') {

            $app_name = '趣聊';
        } elseif ($http_host == 'ymapi.miyintech.com') {

            $app_name = '有魅';
        } else {

            $app_name = '测试服';
        }


        $this->view->app_name = $app_name;
    }


    public function isAuth()
    {
        $user_info =  $this->session->get("user_info");
        
        $this->view->user_info = $user_info;


        if($user_info == false)
        {
            $this->logout();
        }
    
        return $user_info;
    }
    
    public function logout()
    {
      
        $this->session->destroy();
        $this->response->redirect( '/index.php?_url=/mgr/home/login' );
    }
    

}
