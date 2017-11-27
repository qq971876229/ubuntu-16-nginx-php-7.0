<?php





class config
{


    const app = array
    (

        //-------------
        "app_name"=>"testapi",
        "app_name_cn"=>"测试",
        "app_download_url" => "http://app.qq.com/#id=detail&appid=1106325768",
        "app_download_url_ios" => "https://itunes.apple.com/cn/app/id1263845552",
        "ver"=>"1",
        "app_key" => 'jjfdf3343#DD!!@jljclj#D45kjlcDD9991()k1G',//签名加密




        //-云通信----------
        "live_app_id" => '1400032212',  //测试服通信id
//       "live_app_id" => '1400037503',   //正式服通信id
        "live_account_type" => '13219', //测试服
//       "live_account_type" => '14554', //正式服
        "live_admin" => 'livemgr',//直播管理员
        "live_service"=>'service',


        //测试服
        "live_private_key"=>"-----BEGIN PRIVATE KEY-----
MIGEAgEAMBAGByqGSM49AgEGBSuBBAAKBG0wawIBAQQgpLEEyYiKkVo/Ven/jrga
WVLqVs9EWuDw7Lk7Cpbj/FqhRANCAASxNBIEG2wyPizalAlsFuZaGM3ZedwJf+lO
/ZvcEZNG0T+ltsUup9+aezDrHm4KBcL1MLbEauPjcFGC/WWVrcxU
-----END PRIVATE KEY-----",


        //正式服
//       "live_private_key"=>"-----BEGIN PRIVATE KEY-----
//MIGEAgEAMBAGByqGSM49AgEGBSuBBAAKBG0wawIBAQQgBkOAYy5qLXliSS4wBUpx
//jUhW+CpnWeT+O8ho4vIXm7ihRANCAAQjOoYGm1f+kyhSPBawdcIqkg4hbGWFRtU6
//C1KE3WW4VaqkJbKy5dW+xZttoLMniJKlBULBMTE5DVtgiHfvYNer
//-----END PRIVATE KEY-----",


        //测试服
        "live_public_key"=>"-----BEGIN PUBLIC KEY-----
MFYwEAYHKoZIzj0CAQYFK4EEAAoDQgAEsTQSBBtsMj4s2pQJbBbmWhjN2XncCX/p
Tv2b3BGTRtE/pbbFLqffmnsw6x5uCgXC9TC2xGrj43BRgv1lla3MVA==
-----END PUBLIC KEY-----",

        //正式服
//       "live_public_key"=>"-----BEGIN PUBLIC KEY-----
//MFYwEAYHKoZIzj0CAQYFK4EEAAoDQgAEIzqGBptX/pMoUjwWsHXCKpIOIWxlhUbV
//OgtShN1luFWqpCWysuXVvsWbbaCzJ4iSpQVCwTExOQ1bYIh372DXqw==
//-----END PUBLIC KEY-----",


        //--图片--------
        "img_bucket_url" => 'http://livetest-1253795974.picgz.myqcloud.com/',//图片存储空间
        "file_bucket_url" => 'http://livetest-1253795974.cossh.myqcloud.com/',//文件存储空间
        "video_bucket_url" => 'http://livetest-1253795974.cosgz.myqcloud.com/',//视频存储空间
        'SecretId'       => 'AKIDNaYHlzxkHYZJn1GiBWdV8XkiQxFJoh28',//
        'SecretKey'      => 'zkGXZLsABvQggPt64lCMGtFMGuIXhgRx',
        "img_app_id" => '1253795974',//cos空间
        "img_bucket" => 'livetest',//cos空间
        "img_region"=>'gz',//图片区域


        //--直播--------
        "live_bizid"=>"10487",
        "live_push_url_key"=>"a7e5dfea90352f90e4432b515a1893e8",
        "live_url"=>"rtmp://10487.liveplay.myqcloud.com/live/10487_",

        //----短信--------
        "moblie_app_name"=>"趣聊科技",
        "moblie_key"=>"bee3d9b687b37242fe8ead8570e0436c"

    );

    const pay = array('rate'=>0.6, "reference" => 0.06, "reference_view" => 0.3);

    const database = array(
        'adapter'     => 'Mysql',
        "host" => "127.0.0.1",
        "username" => "root",
        "password" => "root",
        "dbname" => "qlnew",
        "charset" => "utf8",
        "collation" => "utf8mb4_general_ci"
    );



    const redis=[
        'host' => '10.0.0.8',
        'port' => '6379',
        'pass'=>'crs-rjf0ksbo:6R#dab#23@33',
        "select"=>0
    ];
    const Mongodb=
        [
            'uri'=>'mongodb://mongouser:kj!lj21*(kjl123@10.66.128.207:27017/admin'
        ];


    const error =
        array("logout"=>100,"live_end"=>101);

    const mess_type =
        array(
            "live_start"=>1,//系统消息
            "gift"=>2,
            "live_accept"=>3,
            "mess"=>4,
            "all_mess"=>5
        );


    const complain_type=
        array(
            "live"=>1,
            "find"=>2,
            "artist"=>3,
            "group"=>4
        );

    const debug=[
        'recharge_multiple' => '100'//100正常比例
    ];


}