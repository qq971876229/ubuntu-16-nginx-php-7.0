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
use App\Lib\System\Transaction;
use App\Lib\System\TxMess;


class usersModel extends baseModel
{

    public function initialize()
    {
        $this->addBehavior(
            new Timestampable(
                array(
                    'beforeCreate' => array(
                        'field' => 'created_at',
                        'format' => 'Y-m-d H:i:sP',
                    ),
                    'afterUpdate' => array(
                        'field' => 'updated_at',
                        'format' => 'Y-m-d H:i:sP',
                    ),
                )
            )
        );
    }


    public function getSource()
    {
        return "users";
    }


    public static function fill($uid, $reference_id, $nickname)
    {
        $user_info = usersModel::findFirst("id = '$uid'");

        $user_info->is_fill = 1;

        if (!$user_info->reference) {

            $user_info->reference = $reference_id;
            $user_info->reference_time = time() + 30000000;
        }


        $user_info->nickname = $nickname;

        $user_info->save();

        self::clear($uid);
    }


    public static function set_cash_account($uid, $cash_account, $cash_name)
    {
        $user_info = usersModel::findFirst("id = '$uid'");

        if (strlen($user_info->cash_account) > 2) {
            App::Input()->error("绑定后不能修改");
        }

        $user_info->cash_account = $cash_account;
        $user_info->cash_name = $cash_name;

        $user_info->save();

    }


    public static function find_user($key, $page)
    {
        $value = usersModel::page($page, "select id from users inckname  where  id like \"%$key%\"", array($key));

        $list = usersDomain::format_simple_user_list($value, "id");

        return $list;

    }

    public static function check_pass($uid, $pass)
    {

        $user_info = usersModel::findFirst("id = '$uid'");

        if (empty($user_info->pass)) {
            self::error("用户不存在");
        }


        $pass_md5 = md5($pass.$user_info->salt);

        $is_login = $user_info->pass == $pass_md5;

        if ($is_login == true) {
            self::edit_pass($uid, $pass);//刷新salt

            return true;
        }


        self::error("密码错误");

    }

    public static function check_nickname($name)
    {
        return $name;

    }

    public static function edit_pass($uid, $pass)
    {

        $user_info = usersModel::findFirst("id = '$uid'");

        $salt = rand(100000, 999999);

        $pass = md5($pass.$salt);

        $user_info->pass = $pass;
        $user_info->salt = $salt;

        $user_info->save();

    }


    public static function login_name2uid($name)
    {
        $user = usersModel::findFirst("login_name='$name'");

        if ($user == false) {
            return false;
        } else {
            return $user->id;
        }
    }

    public static function login_qq2uid($name)
    {

        $user = usersModel::findFirst("login_qq='$name'");

        if ($user == false) {
            return false;
        } else {
            return $user->id;
        }

    }

    public static function login_wx2uid($name)
    {
        $user = usersModel::findFirst("login_wx='$name'");

        if ($user == false) {
            return false;
        } else {
            return $user->id;
        }


    }

    public static function login_wb2uid($name)
    {
        $user = usersModel::findFirst("login_wb='$name'");

        if ($user == false) {
            return false;
        } else {
            return $user->id;
        }

    }

    public static function login_moblie2uid($name)
    {
        $user = usersModel::findFirst("login_moblie='$name'");

        if ($user == false) {
            return false;
        } else {
            return $user->id;
        }

    }


    public static function clear($uid)
    {
        \bcl\redis\base::get_rd()->del("users:base:".$uid);
        \bcl\redis\base::get_rd()->del("users:state:".$uid);
        \bcl\redis\base::get_rd()->del("users:money:".$uid.":can");
        \bcl\redis\base::get_rd()->del("users:money:".$uid.":cash");

        $res_data = App::TxMess()->get_user_info($uid);
        $user = usersModel::findFirst("id=$uid");

        $user->nickname = $res_data->nickname;
        $user->birthday = $res_data->birthday;
        $user->sex = $res_data->sex;

        $user->save();

    }

    /**
     * sync the user info to the redis cache
     * @param $uid
     */
    public static function cache_user($uid){

        $res_data = App::TxMess()->get_user_info($uid);
        $user = usersModel::findFirst("id=$uid");

        $key = "users:base:".$uid;

    }


    public static function edit_price($uid, $price)
    {
        $user_base_info = usersModel::findFirst("id = '$uid'");

        $user_base_info->price = $price;
        $user_base_info->save();
        self::clear($uid);
    }

    public static function edit_disturb($uid, $value)
    {
        $user_base_info = usersModel::findFirst("id = '$uid'");

        $user_base_info->is_disturb = $value;
        $user_base_info->save();
        self::clear($uid);
    }


    public static function info($uid)
    {


        $user = cacheBase::get(

            "users:base:".$uid,
            function () use ($uid) {


                $user_base_info = usersModel::findFirst("id = '$uid'");

                if ($user_base_info == false) {
                    return false;
                }

                $res_data = App::TxMess()->get_user_info($uid);

                $res_data->price = $user_base_info->price;

                $res_data->auth_state = $user_base_info->auth_state;

                $res_data->is_black = $user_base_info->is_black;

                $res_data->is_fill = $user_base_info->is_fill;

                $res_data->is_disturb = $user_base_info->is_disturb;

                $res_data->vod = $user_base_info->vod;

                if ($user_base_info->auth_state == 2) {
                    $res_data->is_live = 1;
                } else {
                    $res_data->is_live = 0;
                }

                return $res_data;


            },
            \configCache::user['info']
        );

        // if the birthday timestamp is not useful，set the birthday 18 years old
//        if($user->birthday<0 || $user->birthday>1508641643){
//            $user->birthday = 915120000;
//        }

        return $user;


    }

    public static function info2($uid)
    {

        return cacheBase::get(
            "users:base:".$uid,
            function () use ($uid) {
            },
            \configCache::user['info']
        );


    }

    public static function get_register_by_date($date, $type = '')
    {


        $start_time = strtotime($date);
        $end_time = strtotime($date.'23:59');
        if ($type == 'ios') {

            $sql = "SELECT count(id) FROM users  WHERE phone_type='ios' AND add_time>=".$start_time." AND add_time<".$end_time;
        } elseif ($type == 'android') {

            $sql = "SELECT count(id) FROM users  WHERE phone_type='android' AND add_time>=".$start_time." AND add_time<".$end_time;
        } else {

            $sql = "SELECT count(id) FROM users  WHERE add_time>=".$start_time." AND add_time<".$end_time;
        }


        $register = moneyModel::DB()->getOne($sql);

        return $register;
    }

    /**
     * get my recommend people number
     * @param $uid
     */
    public static function get_recommend_num($uid)
    {

        $sql = "SELECT count(id) FROM users WHERE reference=$uid";

        $recommend_num = usersModel::DB()->getOne($sql);

        return $recommend_num;


    }

    /**
     * get my recommend people list
     * @param $uid
     */
    public static function get_recommend_list($uid)
    {

        $sql = "SELECT id FROM users WHERE reference=$uid ORDER BY id DESC";


        $id_list = usersModel::DB()->getAll($sql);

        foreach ($id_list as $k => $v) {
            $user = usersModel::info($v->id);
            $id_list[$k]->nickname = $user->nickname;
            $id_list[$k]->img = $user->img;
        }

        return $id_list;


    }


    //---------------------------------------------
    public static function create_user_end($user)
    {

        $live_reference_money = new moneyModel();
        $live_reference_money->set_uid($user->id);

//        $live_reference_money->insert(6,
//            "注册奖励",$user->id,\configPay::money_type['register']);

    }


    public static function create_login_wb_user($login_wb, $nickname, $img, $sex)
    {
        $user = new usersModel();
        $user->nickname = usersModel::check_nickname($nickname);
        $user->img = $img;
        $user->sex = $sex;
        $user->login_wb = $login_wb;
        $user->save();

        self::create_user_end($user);

        return $user->id;
    }

    public static function create_login_qq_user($login_wb, $nickname, $img, $sex)
    {
        $user = new usersModel();
        $user->nickname = usersModel::check_nickname($nickname);
        $user->img = $img;
        $user->sex = $sex;
        $user->login_qq = $login_wb;


        $user->save();

        App::TxMess()->create_tx_user($user->id, $nickname, $img);

        self::create_user_end($user);

        return $user->id;
    }

    public static function create_login_wx_user($login_wb, $nickname, $img, $sex, $reference_id)
    {


        //$user->nickname = usersModel::check_nickname($nickname);
        //$user->img = $img;
        //$user->sex = $sex;

        $user = new usersModel();
        $user->login_wx = $login_wb;
        $user->add_time = time();
        $user->reference = $reference_id;
        $user->save();

        App::TxMess()->create_tx_user($user->id, $nickname, $img);

        self::create_user_end($user);

        return $user->id;
    }


    public static function create_login_name_user($name)
    {
        $user = new usersModel();
        $user->login_name = $name;
        $user->nickname = usersModel::check_nickname($name);
        $user->add_time = time();
        $user->save();

        return $user->id;
    }

    public static function create_login_moblie_user($moblie, $reference)
    {
        $user = new usersModel();
        $user->login_moblie = $moblie;
        $user->add_time = time();

        $reference_user = new usersDomain($reference);


        /*
        if( $reference_user->exists() != false)
        {
            
            $user->reference = $reference;
            $user->reference_time = time()+3600*24*30*6;//6个月有效期
            $transaction = new Transaction();
            
            
            $transaction->run(function()use($transaction,$user,$reference)
            {
                $user->save();
                
                
                $live_reference_money = new moneyModel();
                $live_reference_money->set_uid($reference);
                $transaction->set_commit($live_reference_money);
                
                
               $live_reference_money->insert(7,
                    $user->id."注册推荐奖励",$user->id,\configPay::money_type['reference']);
               
               
               
               App::TxMess()->create_tx_user($user->id, $user->nickname,"");
               
               return true;
                    
                
            },"pay:moblie:".$moblie);
            
        }
        else */


        $user->reference = $reference;
        $user->reference_time = time() + 3600 * 24 * 30 * 6;//6个月有效期

        $user->save();

        App::TxMess()->create_tx_user($user->id, $user->id, "");


        self::create_user_end($user);

        return $user->id;


    }


    public static function edit($uid, $data)
    {
        if (isset($data['rank_live']) || isset($data['rank_user']) || isset($data['xp_user'])
            || isset($data['xp_live']) || isset($data['uumoney']) || isset($data['uubean'])
            || isset($data['id']) || isset($data['pass']) || isset($data['account_id'])
            || isset($data['salt']) || isset($data['is_mgr']) || isset($data['cash_name']) || isset($data['cash_account'])
        ) {
            self::error("不允许修改的值");
        }


        if (isset($data['sex'])) {

            if ($data['sex'] != 0 && $data['sex'] != 1 && $data['sex'] != 2) {
                App::Input()->error("sex字段只能是0,1,2");
            }
        }


        $user_info = usersModel::findFirst("id = '$uid'");

        if ($user_info == false) {
            return false;
        }


        if (empty($data) == false) {
            foreach ($data as $k => $v) {
                $user_info->$k = $v;
            }
            $user_info->save();

        }


        $tm_info = new \stdClass();
        $tm_info->nickname = $user_info->nickname;
        $tm_info->img = $user_info->img;

        $res = App::TxMess()->update_user_info($uid, $tm_info);


        \bcl\redis\base::get_rd()->del("users:base:".$uid);
    }


    static function auth_check($uid, $state, $mess)
    {
        $user = usersModel::findFirst("id='$uid'");

        if ($user == false) {
            return App::Input()->error("用户不存在");
        }


        if ($user->auth_state == 2) {
            return App::Input()->error("已经审核");
        }

        if ($state <> 0 && $state <> 2) {
            return App::Input()->error("状态必须是0或1");
        }


        if ($state == 0) {
            App::TxMess()->system_mess($uid, "主播申请被驳回:".$mess);
        }

        if ($state == 2) {
            App::TxMess()->system_mess($uid, "主播申请通过");
        }


        $user->auth_state = $state;
        $user->save();

        \bcl\redis\base::get_rd()->del("users:base:".$uid);

    }

    static function auth($uid, $img, $vod)
    {

        $user = usersModel::findFirst("id='$uid'");

        if ($user == false) {
            return App::Input()->error("用户不存在");
        }


        if ($user->auth_state == 2) {
            return App::Input()->error("已经审核");
        }


        $user->img = $img;
        $user->vod = $vod;

        $user->auth_state = 1;
        $user->save();

        \bcl\redis\base::get_rd()->del("users:base:".$uid);

        return true;


    }

    public static function random($num, $sex)
    {

        $value = self::DB()->getAll("select id from users ORDER BY RAND()  LIMIT 100");

        $list = usersDomain::format_simple_user_list($value, "id");

        return $list;


    }


    public static function get_reference($uid)
    {
        $value = self::DB()->getOne("select count(*) from users where reference=?", array($uid));

        $money = self::DB()->getOne("select sum(value) from money where uid=? and type=40", array($uid));


        $data = new \stdClass();

        if ($money == null) {
            $money = 0;
        }


        if ($value == null) {
            $value = 0;
        }

        $data->num = $value;
        $data->money = $money;

        return $data;

    }



    public static function get_user_list_pc($page, $key, $is_live, $is_view, $online=0)
    {


        $select = "";

        if ($is_live == true && $is_view == true) {


        } else {
            if ($is_live == false && $is_view == false) {
                return [];
            } else {
                if ($is_live == true) {
                    $select = "and auth_state=2 ";
                } else {
                    if ($is_view == true) {
                        $select = "and auth_state <> 2 ";
                    }
                }
            }
        }


        $sql = "select id,created_at from users where id like '%$key%'  $select AND status<>'delete' ";

        // if the user online
        if ($online === 1) {

            $request_time = time() - 5 * 60;  // request time in 5 minutes
            $sql .= " AND request_time>$request_time";
        }



        $list = usersModel::page_pc($page, $sql);


        $list['list'] = usersDomain::format_simple_user_list($list['list'], "id");



        foreach ($list['list'] as $k => $v) {
            $list['list'][$k]->img = \config::app['img_bucket_url'].$list['list'][$k]->img;

            if ($list['list'][$k]->is_live == 1) {
                $list['list'][$k]->carded = "主播";
                $list['list'][$k]->auth_action = "取消主播";
            } else {
                $list['list'][$k]->carded = "观众";
                $list['list'][$k]->auth_action = "成为主播";

            }


            $id = $list['list'][$k]->id;

            $user_info = usersModel::findFirst("id = '$id'");


            if ($user_info->is_black == 1) {
                $list['list'][$k]->black_name = "正常";
            } else {
                $list['list'][$k]->black_name = "禁用";
            }

            $list['list'][$k]->recharge = logPayModel::get($id);

        }


        return $list;

    }


    public static function get_live_list_pc($page)
    {
//        $select = "";
//        $select = "and auth_state=2 ";

//        if($is_live == true && $is_view == true)
//        {
//
//
//        }
//        else if($is_live == false && $is_view == false)
//        {
//            return [];
//        }
//        else if($is_live == true)
//        {
//            $select = "and auth_state=2 ";
//        }
//
//        else if($is_view == true)
//        {
//            $select = "and auth_state <> 2 ";
//        }


        $sql = "select id,created_at from users where auth_state=2";

        $list = usersModel::page_pc($page, $sql);


        $list['list'] = usersDomain::format_simple_user_list($list['list'], "id");


        foreach ($list['list'] as $k => $v) {
            $list['list'][$k]->img = \config::app['img_bucket_url'].$list['list'][$k]->img;

            if ($list['list'][$k]->is_live == 1) {
                $list['list'][$k]->carded = "主播";
//                $list['list'][$k]->auth_action = "取消主播";
            } else {
                $list['list'][$k]->carded = "观众";
//                $list['list'][$k]->auth_action = "成为主播";

            }


            $id = $list['list'][$k]->id;

            $user_info = usersModel::findFirst("id = '$id'");


            if ($user_info->is_black == 1) {
                $list['list'][$k]->black_name = "正常";
            } else {
                $list['list'][$k]->black_name = "禁用";
            }

            $list['list'][$k]->recharge = logPayModel::get($id);

        }


        return $list;

    }


    public static function set_black($uid)
    {

        $user_info = usersModel::findFirst("id = '$uid'");

        if ($user_info->is_black == 0) {
            $user_info->is_black = 1;
        } else {
            $user_info->is_black = 0;
        }

        $user_info->save();

        \bcl\redis\base::get_rd()->del("users:base:".$uid);

    }

    /**
     * auth_state 2=host   1=audience
     * @param $uid
     */
    public static function cancle_host($uid)
    {

        $user_info = usersModel::findFirst("id = '$uid'");

        if ($user_info->auth_state == 2) {
            $user_info->auth_state = 1;
        } else {
            $user_info->auth_state = 2;
        }

        $user_info->save();

        \bcl\redis\base::get_rd()->del("users:base:".$uid);

    }

    /**
     * delete_user
     * @param $uid
     */
    public static function delete_user($uid)
    {

        $user_info = usersModel::findFirst("id = '$uid'");

        $user_info->login_wx = $user_info->login_wx.'|'.$uid;
        $user_info->login_moblie = $user_info->login_moblie.'|'.$uid;
        $user_info->status = 'delete';

        $user_info->save();

        \bcl\redis\base::get_rd()->del("users:base:".$uid);

    }

    /**
     * auth_state 2=host   1=audience
     * @param $uid
     */
    public static function set_host_type($uid, $host_type)
    {

        $user_info = usersModel::findFirst("id = '$uid'");

        $user_info->host_type = $host_type;

        $user_info->save();

        \bcl\redis\base::get_rd()->del("users:base:".$uid);

    }

    /**
     * if user is stick,cancel the stick
     * if user is not stick,stick the user
     * @param $uid
     */
    public static function set_stick($uid)
    {

        $user_info = usersModel::findFirst("id = '$uid'");

        if ($user_info->stick == 1) {

            $user_info->stick = null;
            $user_info->stick_time = null;

        } else {
            $user_info->stick = 1;
            $user_info->stick_time = time();
        }

        $user_info->save();

        \bcl\redis\base::get_rd()->del("users:base:".$uid);

    }


    public static function get_apply_list_pc($page)
    {
        $list = usersModel::page_pc($page, "select * from users where auth_state=1");

        $list['list'] = usersDomain::format_simple_user_list($list['list'], "id");

        foreach ($list['list'] as $k => $v) {
            $list['list'][$k]->img = \config::app['img_bucket_url'].$list['list'][$k]->img;

        }

        return $list;
    }


    /**
     * get the user's balance
     * @param $user_id
     * @return bool|mixed|string
     */
    public static function get_balance($user_id){

        $user_balance_key = "user_".$user_id."_balance";
        $user_balance = cacheBase::redis_get($user_balance_key);
        if(!$user_balance){

            $user_balance_sql = "SELECT SUM(value) FROM money WHERE uid=$user_id";
            $user_balance = moneyModel::DB()->getOne($user_balance_sql);
            $cash_money = moneyModel::get_cash_money2($user_id);
            $user_balance -= $cash_money;

            cacheBase::redis_set($user_balance_key,$user_balance);
        }

        return round($user_balance,2);

    }


    /**
     * set the user's balance
     * @param $user_id
     * @param $balance
     * @return float
     */
    public static function update_balance($user_id,$balance){

        if($balance<0){
            $balance = 0;
        }else{

            $cash_money = moneyModel::get_cash_money2($user_id);
            $balance -= $cash_money;
        }

        $user_balance_key = "user_".$user_id."_balance";
        cacheBase::redis_set($user_balance_key,$balance);

        return round($balance,2);

    }


    /**
     * sync the database balance to redis balance
     * @param $user_id
     * @return bool|mixed|string|void
     */
    public static function sync_balance($user_id){

        $user_balance_sql = "SELECT SUM(value) FROM money WHERE uid=$user_id";
        $user_balance = moneyModel::DB()->getOne($user_balance_sql);

        if($user_balance){
            $user_balance = self::update_balance($user_id,$user_balance);
        }else{
            $user_balance = self::get_balance($user_id);
        }

        return round($user_balance,2);

    }


    /**
     * get the max money give to the live in a vod
     * @param $user_id
     * @return bool|mixed|string
     */
    public static function get_vod_max_money($user_id){

        $vod_max_money_key = "vod_start".$user_id."_balance";

        $vod_max_money = cacheBase::redis_get($vod_max_money_key);
        if(!$vod_max_money){

            $user_balance_sql = "SELECT SUM(value) FROM money WHERE uid=$user_id";
            $vod_max_money = moneyModel::DB()->getOne($user_balance_sql);
            cacheBase::redis_set($vod_max_money_key,$vod_max_money);
        }

        return round($vod_max_money,2);

    }


    /**
     * set the max money give to the live in a vod
     * @param $user_id
     * @param $money
     * @return float
     */
    public static function update_vod_max_money($user_id,$money){

        if($money<0){
            $money = 0;
        }

        $vod_max_money_key = "vod_start".$user_id."_balance";
        cacheBase::redis_set($vod_max_money_key,$money);

        return round($money,2);

    }





}
