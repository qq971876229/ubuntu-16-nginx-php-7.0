<?php
use Phalcon\DI\FactoryDefault\CLI as CliDI;
use Phalcon\CLI\Console as ConsoleApp;
use Phalcon\Loader;

define('VERSION', '1.0.0');

//使用CLI工厂类作为默认的服务容器
$di = new CliDI();

// 定义应用目录路径
defined('APP_PATH')
|| define('APP_PATH', realpath(dirname(__FILE__)));


//include APP_PATH."/config/config.php";


$http_host = $_SERVER['HTTP_HOST'];


if ($http_host == 'testapi.miyintech.com') {

    include APP_PATH."/config/config_dev.php";

} elseif ($http_host == 'qlapi.miyintech.com') {

    include APP_PATH."/config/config_online.php";

} elseif ($http_host == 'ymapi.miyintech.com') {

    include APP_PATH."/config/config_youmei.php";

} else {

    include APP_PATH."/config/config.php";

}


include APP_PATH."/config/configPay.php";
include APP_PATH."/config/configCache.php";

include APP_PATH.'/config/loader.php';


$loader->registerDirs(
    array(
        APP_PATH.'/tasks',
    )
);


$di->setShared(
    'db',
    function () {
        return App\Models\baseModel::DB()->get_conn();
    }
);


// 创建console应用
$console = new ConsoleApp();
$console->setDI($di);

/**
 * 处理console应用参数
 */
$arguments = array();
foreach ($argv as $k => $arg) {
    if ($k == 1) {
        $arguments['task'] = $arg;
    } elseif ($k == 2) {
        $arguments['action'] = $arg;
    } elseif ($k >= 3) {
        $arguments['params'][] = $arg;
    }
}

// 定义全局的参数， 设定当前任务及action
define('CURRENT_TASK', (isset($argv[1]) ? $argv[1] : null));
define('CURRENT_ACTION', (isset($argv[2]) ? $argv[2] : null));

try {
    // 处理参数
    $console->handle($arguments);
} catch (\Phalcon\Exception $e) {
    echo $e->getMessage();
    exit(255);
}