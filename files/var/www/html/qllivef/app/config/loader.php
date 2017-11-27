<?php


$loader = new \Phalcon\Loader();


/**
 * We're a registering a set of directories taken from the configuration file
 */


$loader->registerNamespaces(

    array(
        'App\Controllers' => APP_PATH.'/controllers/',
        'App\Models' => APP_PATH.'/models/',
        'App\RModels' => APP_PATH.'/rmodels/',
        'App\MModels' => APP_PATH.'/mmodels/',
        'App\Domain' => APP_PATH.'/domain/',
        'bcl' => APP_PATH.'/bcl/',
        'App\Lib' => APP_PATH.'/lib/',
    )
);

$loader->register();

//$http_host = $_SERVER['HTTP_HOST'];


//if ($http_host == 'testapi.miyintech.com') {
//    \bcl\redis\base::config(
//        \config_dev::redis['host'],
//        \config_dev::redis['port'],
//        \config_dev::redis['pass'],
//        \config_dev::redis['select']
//    );
//} elseif ($http_host == 'qlapi.miyintech.com') {
//    \bcl\redis\base::config(
//        \config_online::redis['host'],
//        \config_online::redis['port'],
//        \config_online::redis['pass'],
//        \config_online::redis['select']
//    );
//} elseif ($http_host == 'ymapi.miyintech.com') {
//    \bcl\redis\base::config(
//        \config_youmei::redis['host'],
//        \config_youmei::redis['port'],
//        \config_youmei::redis['pass'],
//        \config_youmei::redis['select']
//    );
//} else {
    \bcl\redis\base::config(
        \config::redis['host'],
        \config::redis['port'],
        \config::redis['pass'],
        \config::redis['select']
    );
//}







