<?php




use Phalcon\Di\FactoryDefault;




error_reporting(E_ALL);

define('BASE_PATH', dirname(__DIR__));
define('APP_PATH', BASE_PATH . '/app');

ini_set("display_errors", "On");
//require APP_PATH."/vendor/autoload.php";


date_default_timezone_set ("Asia/Shanghai");



try {

    
 
    
    /**
     * The FactoryDefault Dependency Injector automatically registers
     * the services that provide a full stack framework.
     */
    $di = new FactoryDefault();

    
    include APP_PATH . "/config/config.php";
    include APP_PATH . "/config/configLive.php";
    include APP_PATH . "/config/configPay.php";
    include APP_PATH . "/config/configCache.php";
    

    
    /**
     * Read services
     */
    include APP_PATH . "/config/services.php";

    /**
     * Get config service for use in inline setup below
     */
    $config = $di->getConfig();

    /**
     * Include Autoloader
     */
    include APP_PATH . '/config/loader.php';

    /**
     * Handle the request
     */
    $application = new \Phalcon\Mvc\Application($di);
    
    
    echo $application->handle()->getContent();

} catch (\Exception $e) {
    echo $e->getMessage() . '<br>';
    echo '<pre>' . $e->getTraceAsString() . '</pre>';
}
