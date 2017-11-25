<?php

namespace App\Controllers\mgr;

use App\Domain\payDomain;
use App\Lib\System\App;
use App\Models\moneyModel;
use App\Models\rechargeCalcModel;
use App\Models\usersMgrModel;
use App\Domain\usersDomain;
use Phalcon\Paginator\Adapter\Model as PaginatorModel;
use Phalcon\Paginator\Adapter\NativeArray as PaginatorArray;
use Phalcon\Paginator\Adapter\QueryBuilder as PaginatorQueryBuilder;

class PayController extends baseController
{


    public function RechargeAction()
    {
        $user_info = $this->isAuth();

        if ($user_info->login_name != 'root' && $user_info->login_name != 'wxx') {
            exit('没有权限');
        }
    }

    public function cashAction()
    {
        $user_info = $this->isAuth();

        if ($user_info->login_name != 'root' && $user_info->login_name != 'wxx') {
            exit('没有权限');
        }
    }

    public function add_moneyAction()
    {
        $user_info = $this->isAuth();

        if ($user_info->login_name != 'root' && $user_info->login_name != 'wxx') {
            exit('没有权限');
        }

        $this->view->mess = "充值";

        $uid = $this->request->getPost('uid');
        $money = $this->request->getPost('money');

        $user = new usersDomain($uid);

        if ($user->simple_info() == false) {
            $this->view->mess = "用户不存在";
        } else {

            payDomain::pay_debug_add_money($uid, $money);
            $this->view->mess = "添加成功";
        }

    }


    public function manageAction()
    {

        $user_info = $this->isAuth();

        if ($user_info->login_name != 'root' && $user_info->login_name != 'wxx') {
            exit('没有权限');
        }


        $currentPage = $this->request->get('page', 'int', 1);

        if ($currentPage == 1) {

            $today_date = date("Y-m-d", time());
            rechargeCalcModel::create_date_calc($today_date);

            // yesterday data must and just refresh today one time
            $yesterday_date = date("Y-m-d", strtotime('-1 day'));
            $yesterday = rechargeCalcModel::findFirst("date='".$yesterday_date."'");
            if($yesterday){

                if($yesterday->updated_time<strtotime($today_date)){

                    rechargeCalcModel::create_date_calc($yesterday_date);
                }
            }else{

                rechargeCalcModel::create_date_calc($yesterday_date);
            }



        }

        $list = rechargeCalcModel::find(
            array(
                "order" => "date desc",
            )
        );

        $paginator = new PaginatorModel(
            array(
                "data" => $list,
                "limit" => 10,
                "page" => $currentPage,
            )
        );

        // Get the paginated results
        $page = $paginator->getPaginate();

        $this->view->page = $page;

    }

    public function balanceAction()
    {

        $user_info = $this->isAuth();

        if ($user_info->login_name != 'root' && $user_info->login_name != 'wxx') {
            exit('没有权限');
        }

        $host_sql = "SELECT sum(value) from money LEFT JOIN users ON money.uid=users.id where users.auth_state=2";
        $host_balance = moneyModel::DB()->getOne($host_sql);

        $view_sql = "SELECT sum(value) from money LEFT JOIN users ON money.uid=users.id where users.auth_state<>2";
        $view_balance = moneyModel::DB()->getOne($view_sql);


        $total_balance = floatval($host_balance) + floatval($view_balance);

        $this->view->total_balance = $total_balance;
        $this->view->host_balance = $host_balance;
        $this->view->view_balance = $view_balance;


    }


}
