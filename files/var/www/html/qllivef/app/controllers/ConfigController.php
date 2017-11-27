<?php

namespace App\Controllers;

use App\Models\settingModel;
use App\Models\usersModel;
use App\Lib\System\App;
use App\Domain\usersDomain;
use App\Domain\rankListDomain;
use App\Models\adModel;
use bcl\redis\cacheBase;


class ConfigController extends baseController
{

    /**
     * return the app config to app
     */
    public function appAction()
    {

        $configflag = App::Input()->get("configflag");

        $http_host = $_SERVER['HTTP_HOST'];

        if ($http_host == 'ymapi.miyintech.com') {

            $data = $this->ymConfig($configflag);

        } else {

            $data = $this->qlConfig($configflag);
        }

        App::Input()->out($data);

    }


    public function qlConfig($configflag)
    {

        $config_server = cacheBase::redis_get("setting_configflag");
        $app_store_version = settingModel::get(4)->input_value;
        $new_version = settingModel::get(5)->input_value;
        $price_max = settingModel::get(6)->input_value;

        $price_list = settingModel::get(7)->input_value;
        $price_list = explode(',',$price_list);

        $recharge_list = settingModel::get(8)->input_value;
        $recharge_list = explode(',',$recharge_list);

        $service_charge_tips = settingModel::get(14)->input_value;
        $video_max = settingModel::get(15)->input_value;
        $update_info = settingModel::get(16)->input_value;
        $update_addr = settingModel::get(17)->input_value;
        $serviceQQ = settingModel::get(18)->input_value;
        $serviceWX = settingModel::get(19)->input_value;
        $about_us = settingModel::get(20)->input_value;
        $url_reword = settingModel::get(21)->input_value;
        $url_protocol = settingModel::get(22)->input_value;
        $user_protocol = settingModel::get(23)->input_value;
        $url_share = settingModel::get(24)->input_value;
        $url_code_share = settingModel::get(25)->input_value;
        $share_info = settingModel::get(26)->input_value;
        $is_custom = settingModel::get(28)->input_value;
        $tips1 = settingModel::get(29)->input_value;
        $is_baidu = settingModel::get(30)->input_value;
        $is_yinyongbao = settingModel::get(31)->input_value;
        $is_huawei = settingModel::get(58)->input_value;


        if ($configflag != $config_server || $configflag===0) {
            $data = array(
                "app_store_version" => $app_store_version,
                "isbaidu" => $is_baidu,
                "iscustom" => $is_custom,
                "isyinyongbao" => $is_yinyongbao,
                "ishuawei" => $is_huawei,
                "price_max" => $price_max,
                "price_list" => $price_list,
                "recharge_list" => $recharge_list,
                "service_charge_tips" => $service_charge_tips,
                "tips1" => $tips1,
                "video_max" => $video_max,
                "update" => array(
                    "new_version" => $new_version,
                    "update_info" => $update_info,
                    "update_addr" => $update_addr,
                    "mandatory" => 0,
                    "tips_number" => 3,
                ),
                "serviceQQ" => $serviceQQ,
                "serviceWX" => $serviceWX,
                "about_us" => $about_us,
                "reward" => array(
                    array(
                        "title_num" => "奖励一",
                        "title" => "20%消费提成",
                        "content" => "你邀请的人每一笔消费你将获得30%的提成(不包含活动期间充值额外赠送的金额)",
                    ),
                    array(
                        "title_num" => "奖励二",
                        "title" => "10%收入提成",
                        "content" => "你邀请的人每一笔成功收入你将获得10%的提成(只计算实际交易产生的收入，不计任务中心和官方活动的赠送收入)",

                    ),
                ),
                "url" => array(
                    "url_reword" => $url_reword,
                    "url_protocol" => $url_protocol,
                    "user_protocol" => $user_protocol,
                    "url_share" => $url_share,
                    "url_code_share" => $url_code_share,
                    "share_info" => "扫二维码加入趣聊\n记住趣聊号来撩我",


                ),

                "configflag" => $config_server,
                "isnew" => 1,


            );

        } else {
            $data = array(
                "isnew" => 0,
            );

        }



        return $data;


    }


    public function ymConfig($configflag)
    {

        $config_server = cacheBase::redis_get("setting_configflag");
        $app_store_version = settingModel::get(4)->input_value;
        $new_version = settingModel::get(5)->input_value;
        $price_max = settingModel::get(6)->input_value;

        $price_list = settingModel::get(7)->input_value;
        $price_list = explode(',',$price_list);

        $recharge_list = settingModel::get(8)->input_value;
        $recharge_list = explode(',',$recharge_list);

        $service_charge_tips = settingModel::get(14)->input_value;
        $video_max = settingModel::get(15)->input_value;
        $update_info = settingModel::get(16)->input_value;
        $update_addr = settingModel::get(17)->input_value;
        $serviceQQ = settingModel::get(18)->input_value;
        $serviceWX = settingModel::get(19)->input_value;
        $about_us = settingModel::get(20)->input_value;
        $url_reword = settingModel::get(21)->input_value;
        $url_protocol = settingModel::get(22)->input_value;
        $user_protocol = settingModel::get(23)->input_value;
        $url_share = settingModel::get(24)->input_value;
        $url_code_share = settingModel::get(25)->input_value;
        $share_info = settingModel::get(26)->input_value;
        $is_custom = settingModel::get(28)->input_value;
        $tips1 = settingModel::get(29)->input_value;
        $is_baidu = settingModel::get(30)->input_value;
        $is_yinyongbao = settingModel::get(31)->input_value;
        $is_huawei = settingModel::get(58)->input_value;


        if ($configflag != $config_server || $configflag===0) {
            $data = array(
                "app_store_version" => $app_store_version,
                "isbaidu" => $is_baidu,
                "iscustom" => $is_custom,
                "isyinyongbao" => $is_yinyongbao,
                "ishuawei" => $is_huawei,
                "price_max" => $price_max,
                "price_list" => $price_list,
                "recharge_list" => $recharge_list,
                "service_charge_tips" => '小贴士：每笔收入平台将收取40%的手续费',
                "tips1" => $tips1,
                "video_max" => "3",
                "update" => array(
                    "new_version" => $new_version,
                    "update_info" => $update_info,
                    "update_addr" => $update_addr,
                    "mandatory" => 0,
                    "tips_number" => 3,
                ),
                "serviceQQ" => $serviceQQ,
                "serviceWX" => $serviceWX,
                "about_us" => $about_us,
                "reward" => array(
                    array(
                        "title_num" => "奖励一",
                        "title" => "20%消费提成",
                        "content" => "你邀请的人每一笔消费你将获得20%的提成(不包含活动期间充值额外赠送的金额)",
                    ),
                    array(
                        "title_num" => "奖励二",
                        "title" => "10%收入提成",
                        "content" => "你邀请的人每一笔成功收入你将获得10%的提成(只计算实际交易产生的收入，不计任务中心和官方活动的赠送收入)",

                    ),
                ),
                "url" => array(
                    "url_reword" => $url_reword,
                    "url_protocol" => $url_protocol,
                    "user_protocol" => $user_protocol,
                    "url_share" => $url_share,
                    "url_code_share" => $url_code_share,
                    "share_info" => "邂逅美丽，我们约吧！\n填我有魅号,  倾情等你！",


                ),

                "configflag" => $config_server,
                "isnew" => 1,


            );

        } else {
            $data = array(
                "isnew" => 0,
            );

        }


        return $data;


    }


    public function ql_oldConfig($configflag)
    {

        $config_server = '20171016';



        if ($configflag != $config_server) {
            $data = array(
                "app_store_version" => "1.0.8",
                "price_max" => "3.00",
                "price_list" => array('2.00','2.50','3.00'),
                "recharge_list" => array('10.00','30.00','50.00','100.00','200.00','500.00','1000.00','2000.00'),
                "service_charge_tips" => '小贴士：每笔收入平台将收取40%的手续费',
                "video_max" => "3",
                "update" => array(
                    "new_version" => "1.0.8",
                    "update_info" => "发现新版本，是否更新？",
                    "update_addr" => "https://itunes.apple.com/cn/app/id1263845552?mt=8",
                    "mandatory" => 0,
                    "tips_number" => 3,
                ),
                "serviceQQ" => "1967008808",
                "serviceWX" => "douqu828",
                "about_us" => "趣聊是一款即时视频通讯软件，公司以互联网创新模式产品及解决方案为核心，是一家标准化管理，快速发展的科技型企业。公司拥有员工30余人，是一批拥有互联网领域10余年从业经验的专业团队，专注成为技术过硬的互联网技术服务商。",
                "reward" => array(
                    array(
                        "title_num" => "奖励一",
                        "title" => "30%消费提成",
                        "content" => "你邀请的人每一笔消费你将获得30%的提成(不包含活动期间充值额外赠送的金额)",
                    ),
                    array(
                        "title_num" => "奖励二",
                        "title" => "10%收入提成",
                        "content" => "你邀请的人每一笔成功收入你将获得10%的提成(只计算实际交易产生的收入，不计任务中心和官方活动的赠送收入)",

                    ),
                ),
                "url" => array(
                    "url_reword" => "http://testapi.miyintech.com/qllivef/public/index.php?_url=/mgr/home/rule_index",
                    "url_protocol" => "http://testapi.miyintech.com/qllivef/public/index.php?_url=/mgr/home/rule_protocol",
                    "user_protocol" => "http://qlapi.miyintech.com/qllivef/public/index.php?_url=/mgr/home/user_protocol",
//                    "url_share" => "http://qlapi.miyintech.com/qllivef/web/share/index.html",
                    "url_share" => "111.230.127.71/qllivef/web/share/index.html",
                    "url_code_share" => "http://web-1253795974.cosgz.myqcloud.com/shareCode/quChatShareCode.png",
                    "share_info" => "扫二维码加入趣聊\n记住趣聊号来撩我",

                ),

                "configflag" => $config_server,
                "isnew" => 1,


            );

        } else {
            $data = array(
                "isnew" => 0,
            );

        }


        return $data;


    }


    public function ym_oldConfig($configflag)
    {

        $config_server = '20171015';
        if ($configflag != $config_server) {
            $data = array(
                "app_store_version" => "1.0.7",
                "price_max" => "2.00",
                "price_list" => array('1.00','1.50','2.00'),
                "recharge_list" => array('10.00','30.00','50.00','100.00','200.00','500.00','1000.00','2000.00'),
                "service_charge_tips" => '小贴士：每笔收入平台将收取40%的手续费',
                "video_max" => "3",
                "update" => array(
                    "new_version" => "1.0.7",
                    "update_info" => "发现新版本，请卸载当前版本，前往下载",
                    "update_addr" => "https://www.pgyer.com/umei",
                    "mandatory" => 0,
                    "tips_number" => 3,
                ),
                "serviceQQ" => "3119675316",
                "serviceWX" => "life-0828",
                "about_us" => "有魅是一家以互联网创新模式软件产品及解决方案为核心，标准化管理，快速发展的高新技术企业。从金融跨界，同时涉及更多互联网领域的多方面发展型企业。拥有员工300余人，拥有一批互联网领域10余年从业经验的专业团队。专注成为技术过硬，商业模式超前的互联网领军企业。期待您的在线留言或来电咨询!有魅官方微信号（life-0828）。",
                "reward" => array(
                    array(
                        "title_num" => "奖励一",
                        "title" => "30%消费提成",
                        "content" => "你邀请的人每一笔消费你将获得30%的提成(不包含活动期间充值额外赠送的金额)",
                    ),
                    array(
                        "title_num" => "奖励二",
                        "title" => "10%收入提成",
                        "content" => "你邀请的人每一笔成功收入你将获得10%的提成(只计算实际交易产生的收入，不计任务中心和官方活动的赠送收入)",

                    ),
                ),
                "url" => array(
                    "url_reword" => "http://ymapi.miyintech.com//qllivef/public/index.php?_url=/mgr/home/rule_index_youmei",
                    "url_protocol" => "http://ymapi.miyintech.com//qllivef/public/index.php?_url=/mgr/home/rule_protocol_youmei",
//                    "url_share" => "http://ymapi.miyintech.com/qllivef/web/share/index.html",
                    "url_share" => "111.230.2.24/qllivef/web/share/index.html",
                    "url_code_share" => "http://web-1253795974.cosgz.myqcloud.com/shareCode/umeiShareCode.png",
                    "share_info" => "邂逅美丽，我们约吧！\n填我有魅号,倾情等你！",

                ),

                "configflag" => $config_server,
                "isnew" => 1,


            );

        } else {
            $data = array(
                "isnew" => 0,
            );

        }


        return $data;


    }


}

