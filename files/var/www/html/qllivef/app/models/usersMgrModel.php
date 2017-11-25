<?php

namespace App\Models;
use Phalcon\Mvc\Model;
use Phalcon\Mvc\Model\Resultset\Simple as Resultset;
use App\RModels\usersRModel;
use App\RModels\users_simple_RModel;
use Phalcon\Mvc\Model\Behavior\Timestampable;
use bcl\redis\cacheBase;
use App\Lib\System\App;
use App\Domain\usersDomain;






class usersMgrModel extends baseModel
{


    public function initialize()
    {
        $this->addBehavior(new Timestampable(
            array(
                'beforeCreate' => array(
                    'field' => 'created_at',
                    'format' => 'Y-m-d H:i:sP'
                ),
                'afterUpdate' => array(
                    'field' => 'updated_at',
                    'format' => 'Y-m-d H:i:sP')
            )
            ));
    }



    public function getSource()
    {
        return "users_mgr";
    }







    public static function add_user($name,$pass)
    {
        $user = new usersMgrModel();


        $salt = rand(100000,999999);

        $pass_md5 = md5($pass.$salt);


        //echo "#".$pass_md5."#".$pass."#".$salt;
        //die();

        $user->pass = $pass_md5;
        $user->salt = $salt;
        $user->login_name = $name;

        $user->save();

    }


    public static  function check_pass($name,$pass)
    {


        $user_info =  usersMgrModel::findFirst("login_name = '$name'");


        if(empty($user_info->pass))
        {

             return false;

        }

 
        $pass_md5 = md5($pass.$user_info->salt);

        $is_login = $user_info->pass == $pass_md5;

        // the test don't check password
        $http_host = $_SERVER['HTTP_HOST'];


        if ($http_host == 'testapi.chumao.net') {

            if($pass == '1111'){

                $is_login = true;
            }

        }
         
        if($is_login == true)
        {
            usersMgrModel::edit_pass($name,$pass);//刷新salt

            unset($user_info->pass);
            unset($user_info->salt);

            return $user_info;
        }

       // die("#2");

        return false;

    }


    public static function edit_pass($name,$pass)
    {

         $user_info =  usersMgrModel::findFirst("login_name = '$name'");

        $salt = rand(100000,999999);

        $pass_md5 = md5($pass.$salt);


        //echo "#".$pass_md5."#".$pass."#".$salt;
        //die();

        $user_info->pass = $pass_md5;
        $user_info->salt = $salt;

        $user_info->save();

    }


}
