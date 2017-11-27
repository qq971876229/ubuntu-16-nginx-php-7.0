<?php


class config
{


    const app = array
    (

        //-------------
        "app_name" => "ql",
        "app_name_cn" => "趣聊",
//        "app_download_url" => "http://app.qq.com/#id=detail&appid=1106325768",
//        "app_download_url" => "https://mobile.baidu.com/item?docid=22663109&source=pc",
        "app_download_url" => "http://web-1253795974.cosgz.myqcloud.com/apk/quliao.apk",
        "app_download_url_ios" => "https://itunes.apple.com/cn/app/id1263845552",
        "ver" => "1",
        "app_key" => 'jjfdf3343#DD!!@jljclj#D45kjlcDD9991()k1G',


        //----------
        "live_app_id" => '1400032212',
        "live_account_type" => '13219',
        "live_admin" => 'livemgr',//
        "live_service" => 'service',

        "live_private_key" => "-----BEGIN PRIVATE KEY-----
MIGEAgEAMBAGByqGSM49AgEGBSuBBAAKBG0wawIBAQQgpLEEyYiKkVo/Ven/jrga
WVLqVs9EWuDw7Lk7Cpbj/FqhRANCAASxNBIEG2wyPizalAlsFuZaGM3ZedwJf+lO
/ZvcEZNG0T+ltsUup9+aezDrHm4KBcL1MLbEauPjcFGC/WWVrcxU
-----END PRIVATE KEY-----",


        "live_public_key" => "-----BEGIN PUBLIC KEY-----
MFYwEAYHKoZIzj0CAQYFK4EEAAoDQgAEsTQSBBtsMj4s2pQJbBbmWhjN2XncCX/p
Tv2b3BGTRtE/pbbFLqffmnsw6x5uCgXC9TC2xGrj43BRgv1lla3MVA==
-----END PUBLIC KEY-----",


        //----------
        "img_bucket_url" => 'http://qlimg1-1253795974.picgz.myqcloud.com/',//
        "file_bucket_url" => 'http://qlimg1-1253795974.cosgz.myqcloud.com/',//
        "video_bucket_url" => 'http://qlimg1-1253795974.cosgz.myqcloud.com/',//视频存储空间

        'SecretId' => 'AKIDNaYHlzxkHYZJn1GiBWdV8XkiQxFJoh28',//


        'SecretKey' => 'zkGXZLsABvQggPt64lCMGtFMGuIXhgRx',
        "img_app_id" => '1253795974',//
        "img_bucket" => 'qlimg1',//
        "img_region" => 'gz',//


        //----------
        "live_bizid" => "10487",
        "live_push_url_key" => "a7e5dfea90352f90e4432b515a1893e8",
        "live_url" => "rtmp://10487.liveplay.myqcloud.com/live/10487_",

        //------------
        "moblie_app_name" => "趣聊科技",
        "moblie_key" => "bee3d9b687b37242fe8ead8570e0436c",

        //----------
        "qq_app_id" => "1106325768",
        "qq_app_key" => "4jwqrD5U1xODkl8N",


        //-------
        "pingxx_app_id" => "app_znPafTnnLajPW9Sy",
        "pingxx_api_key" => "sk_live_envvT09SK484zjHCmD9mL0a9",
        "pingxx_rsa_public_key" => "-----BEGIN PUBLIC KEY-----
MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAslGfbHDp8Z4AGKfspU6r
Ymc7Dj3e6E0vK825floCeAwVHtxkCXc+TDNawMNkKdtbpzYxUIhgxYShKZhI3cPH
5unqnemwAwGoqaxxUwQ0XPGziLUXUhi6Kpy9Cb0VPJyYh+99QipeRCxSUqKspa2s
WsxRVI2mFTx4FJpeY8R+qF3es9KvInZIhctxTyQRYIQ36AuYH94bJbDIGRYOjXnv
jSHDqfLUHmnAoUfreH/tkEUfmKqSIXqLl9UABUaTZ1YdPR1YJi178bNMi66azC2K
WJyrBZmyQQwsT0MZABYcBOZgneXVqf32XqoEuIp+bun6qRs3+s05Q/mh2fW1YoQ0
gwIDAQAB
-----END PUBLIC KEY-----"

    );

    const pay = array('rate' => 0.6, "reference" => 0.06, "reference_view" => 0.2);

    const database = array(
        'adapter' => 'Mysql',
        "host" => "10.0.0.7",
        "username" => "root",
        "password" => "33#dd(fdfdsssd",
        "dbname" => "qlnew",
        "charset" => "utf8mb4",
        "collation" => "utf8mb4_general_ci"
    );


    const redis = [
        'host' => '10.0.0.8',
        'port' => '6379',
        'pass' => 'crs-rjf0ksbo:6R#dab#23@33',
        "select" => 2
    ];
    const Mongodb =
        [
            'uri' => 'mongodb://mongouser:kj!lj21*(kjl123@10.66.128.207:27017/admin'
        ];


    const error =
        array("logout" => 100, "live_end" => 101);

    const mess_type =
        array(
            "live_start" => 1,
            "gift" => 2,
            "live_accept" => 3,
            "mess" => 4,
            "all_mess" => 5
        );


    const complain_type =
        array(
            "live" => 1,
            "find" => 2,
            "artist" => 3,
            "group" => 4
        );

    const debug = [
        'recharge_multiple' => '100'
    ];
}