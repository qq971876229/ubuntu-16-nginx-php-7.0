<?php
/*
 * Modified: prepend directory path of current file, because of this file own different ENV under between Apache and command line.
 * NOTE: please remove this comment.
 */
defined('BASE_PATH') || define('BASE_PATH', getenv('BASE_PATH') ?: realpath(dirname(__FILE__) . '/../..'));
defined('APP_PATH') || define('APP_PATH', BASE_PATH . '/app');

return new \Phalcon\Config
(
  
    [
     /*   
    'mysql' => [
        'adapter'     => 'Mysql',
        "host" => "10.66.176.73",
        "username" => "root",
        "password" => "jj121jd@#ASD00hh",
        "dbname" => "live1",
        "charset" => "utf8",
        "collation" => "utf8mb4_general_ci"
    ],
    'application' => [
        'appDir'         => APP_PATH . '/',
        'controllersDir' => APP_PATH . '/controllers/',
        'modelsDir'      => APP_PATH . '/models/',
        'migrationsDir'  => APP_PATH . '/migrations/',
        'viewsDir'       => APP_PATH . '/views/',
        'pluginsDir'     => APP_PATH . '/plugins/',
        'libraryDir'     => APP_PATH . '/library/',
        'cacheDir'       => BASE_PATH . '/cache/',

        // This allows the baseUri to be understand project paths that are not in the root directory
        // of the webpspace.  This will break if the public/index.php entry point is moved or
        // possibly if the web server rewrite rules are changed. This can also be set to a static path.
        'baseUri'        => preg_replace('/public([\/\\\\])index.php$/', '', $_SERVER["PHP_SELF"])."public/index.php?_url=/"
        
    ],
    'img_url'=>'http://flower-1252568513.image.myqcloud.com/',
    'redis'=>[
        'host' => '10.66.105.39',
        'port' => '6379',
        'pass'=>'crs-6fyan4ke:123456789abcd',
        ],
      'Mongodb'=>
        [
            'uri'=>'mongodb://mongouser:kj!lj21*(kjl123@10.66.128.207:27017/admin'
        ],
       'App'=>
        [
            "ver"=>"3",
            "live_app_id" => '1400014657',
            "live_account_type" => '7422',
            "live_admin" => 'husheng',//直播管理员
            "app_key" => 'jjfdf3343#DD!!@jljclj#D45kjlcDD9991()k1G',//直播管理员
            "img_bucket" => 'liveimg',//直播管理员
            "img_bucket_url" => 'http://liveimg-10065661.image.myqcloud.com/',//图片存储空间
            'SecretId'       => 'AKIDQuq4bColQLNavMm08NjO1mdbfvNwjhpJ',//
            'SecretKey'      => 'x7kAu1R5ncdkyIlM3xot5NqQ8wsu3IeP',      
        ]*/
]);
