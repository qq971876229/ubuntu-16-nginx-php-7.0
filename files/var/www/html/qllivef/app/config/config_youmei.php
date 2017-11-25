<?php


class config
{


    const app = array
    (

        //-------------
        "app_name" => "ym",
        "app_name_cn" => "有魅",
        "app_download_url" => "http://a.app.qq.com/o/simple.jsp?pkgname=com.miyin.youmei",
        "app_download_url_ios" => "https://www.pgyer.com/umei",
        "ver" => "1",
        "app_key" => 'jjfdf3343#DD!!@jljclj#D45kjlcDD9991()k1G',
       

        //----------
        "live_app_id" => '1400037078',
        "live_account_type" => '14554',
        "live_admin" => 'livemgr',
        "live_service" => 'service',

        "live_private_key" => "-----BEGIN PRIVATE KEY-----
MIGEAgEAMBAGByqGSM49AgEGBSuBBAAKBG0wawIBAQQgIMqOhYVcNH3nc21mq9+i
In9DFCW+jfqGkaFFNRn/FjShRANCAASoZUzTd9Mtq0oFfJXXcpqdZgW0z4dhXvOm
wjkDPEhHF8HFct7d67L3Ri2iMVaSoD1Nq2MVljVM9G6VhhdwkOYS
-----END PRIVATE KEY-----",


        "live_public_key" => "-----BEGIN PUBLIC KEY-----
MFYwEAYHKoZIzj0CAQYFK4EEAAoDQgAEqGVM03fTLatKBXyV13KanWYFtM+HYV7z
psI5AzxIRxfBxXLe3euy90YtojFWkqA9TatjFZY1TPRulYYXcJDmEg==
-----END PUBLIC KEY-----",


        //----------
        "img_bucket_url" => 'http://ymimg-1253795974.picgz.myqcloud.com/',
        "file_bucket_url" => 'http://ymimg-1253795974.cosgz.myqcloud.com/',
        "video_bucket_url" => 'http://ymimg-1253795974.cosgz.myqcloud.com/',//视频存储空间
        'SecretId' => 'AKIDNaYHlzxkHYZJn1GiBWdV8XkiQxFJoh28',//
        'SecretKey' => 'zkGXZLsABvQggPt64lCMGtFMGuIXhgRx',
        "img_app_id" => '1253795974',
        "img_bucket" => 'ymimg',
        "img_region" => 'gz',


        //----------
        "live_bizid" => "10487",
        "live_push_url_key" => "a7e5dfea90352f90e4432b515a1893e8",
        "live_url" => "rtmp://10487.liveplay.myqcloud.com/live/10487_",

        //------------
        "moblie_app_name" => "有魅科技",
        "moblie_key" => "bee3d9b687b37242fe8ead8570e0436c",

        //-------
        "pingxx_app_id" => "app_aHe1uHu94m5SPOy1",
        "pingxx_api_key" => "sk_live_9i98SK4yfXj5yzbXX5jTWLW5",
        "pingxx_rsa_public_key" => "-----BEGIN PUBLIC KEY-----
MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEA4PeE8uzIWLoeyHGR2tew
CGr/7UT9pVI9pIGEwFK5gGHQXP0xSRJu/deKNO+AVBd6CDKWjyNKgCinU1z/1TRr
zcnfiS+WYDCCeU3ukN5I3rDDkhH7GJC0ckybrfunERtcGVAJRyIhTcmmiNg1yrKe
rer9ajvUi5PEZiTwjtxkFPSlQ1SI+eGIR5rMkVAUDOjaPVeRB+uo/EVGRqB7je5H
qX09V+Nv7HW/KFNDfBVp3ugbp/bGaCvOoH4Utvt/+YEUy7DO5g8VDyJKtcCtX917
dEcpksbk1rfY052qyT+vclTzFij9iIwl3/mUZQ9k6MZhhYBDNWsjG8cnJ/kbf9gX
0QIDAQAB
-----END PUBLIC KEY-----",


    );

    const pay = array('rate' => 0.6, "reference" => 0.06, "reference_view" => 0.2);

    const database = array(
        'adapter' => 'Mysql',
        "host" => "10.0.0.7",
        "username" => "root",
        "password" => "33#dd(fdfdsssd",
        "dbname" => "ym",
        "charset" => "utf8",
        "collation" => "utf8mb4_general_ci",
    );


    const redis = [
        'host' => '10.0.0.8',
        'port' => '6379',
        'pass' => 'crs-rjf0ksbo:6R#dab#23@33',
        "select" => 3,
    ];
    const Mongodb =
        [
            'uri' => 'mongodb://mongouser:kj!lj21*(kjl123@10.66.128.207:27017/admin',
        ];


    const error =
        array("logout" => 100, "live_end" => 101);

    const mess_type =
        array(
            "live_start" => 1,
            "gift" => 2,
            "live_accept" => 3,
            "mess" => 4,
            "all_mess" => 5,
        );


    const complain_type =
        array(
            "live" => 1,
            "find" => 2,
            "artist" => 3,
            "group" => 4,
        );

    const debug = [
        'recharge_multiple' => '100',
    ];


}