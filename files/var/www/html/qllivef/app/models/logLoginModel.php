<?php

namespace App\Models;

use Phalcon\Mvc\Model;
use Phalcon\Mvc\Model\Resultset\Simple as Resultset;
use App\App;
use App\Models\usersModel;
use Phalcon\Mvc\Model\Behavior\Timestampable;
use phpDocumentor\Reflection\Types\Integer;
use App\Lib\System\Transaction;
use App\RModels\logRModel;
use App\Domain\usersDomain;

class logLoginModel extends baseModel
{

    public function getSource()
    {
        return "log_login";
    }

    /**
     * add the login in message ,if login today ,just update the update time,if not ,add the login record today
     * @param $uid
     */
    public static function add($uid)
    {

        $login = logLoginModel::findFirst(
            array(
                'conditions' => 'uid=:uid:',
                'bind' => array('uid' => $uid),
                'order' => "id desc",
            )
        );


        if ($login) {
            $today_date = strtotime(date('Y-m-d', time()));
            if ($login->created_time > $today_date) {
                $login->updated_time = time();
                $login->save();
            } else {
                $login = new logLoginModel();
                $login->uid = $uid;
                $login->created_time = time();
                $login->updated_time = time();
                $login->create();
            }
        } else {
            $login = new logLoginModel();
            $login->uid = $uid;
            $login->created_time = time();
            $login->updated_time = time();
            $login->create();

        }

    }


    /**
     *
     * @param $date
     * @param string $type live or view
     * @return mixed
     */
    public static function get_login_by_date($date, $type = 'view')
    {

        $start_time = strtotime($date);
        $end_time = strtotime($date.'23:59');

        if ($type == 'view') {

            $sql = "SELECT count(DISTINCT log_login.uid) FROM log_login LEFT JOIN users ON users.id=log_login.uid WHERE users.auth_state<>2 AND  created_time>=".$start_time." AND created_time<=".$end_time;

        } else {

            $sql = "SELECT count(DISTINCT log_login.uid) FROM log_login LEFT JOIN users ON users.id=log_login.uid WHERE users.auth_state=2 AND  created_time>=".$start_time." AND created_time<=".$end_time;
        }

        $login_num = logLoginModel::DB()->getOne($sql);

        return $login_num;

    }


}
