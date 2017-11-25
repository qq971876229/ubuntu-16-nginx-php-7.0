<?php


use Phalcon\Events\Manager;
use Phalcon\Mvc\View;
use Phalcon\Mvc\View\Engine\Php as PhpEngine;
use Phalcon\Mvc\Url as UrlResolver;
use Phalcon\Mvc\View\Engine\Volt as VoltEngine;
use Phalcon\Mvc\Model\Metadata\Memory as MetaDataAdapter;
use Phalcon\Session\Adapter\Files as SessionAdapter;
use Phalcon\Flash\Direct as Flash;
use App\Models\baseModel;










//---
/**
 * Shared configuration service
 */


$di->setShared('config', function () 
{
    return include APP_PATH . "/config/phconfig.php";
    
});






$di->set('router', function () 
{
    $router = new \Phalcon\Mvc\Router();
        
    
    $router->add(
        "/mgr/:controller/:action/:params",
        array(
            'namespace'  => "App\Controllers\mgr",
            "controller" => 1,
            "action"     => 2,
            "params"     => 3
        )
        );
    
    
  

    return $router;
});



$di->set(
    "url",
    function () 
    {
        
        
        
        
        
        $url = new UrlResolver();
        
        $url->setBaseUri("/qllivef/public");

        return $url;
    },
    true
    );



/**
 * Setting up the view component
 */
$di->setShared('view', function () {
    $config = $this->getConfig();

    $view = new View();
    
    
    $dir =  __DIR__ . "/../../app/views/";
    

    $view->setViewsDir($dir);
    
   $view->registerEngines(
            [
                ".volt" => function ($view, $di) use ($config) 
                {
                    $volt = new VoltEngine($view, $di);

                    $volt->setOptions(
                        [
                            "compiledPath"      =>  __DIR__ . "/../../cache/",
                            "compiledSeparator" => "_",
                        ]
                    );

                    return $volt;
                },

            ]
        );
    
    return $view;
});

/**
 * Database connection is created based in the parameters defined in the configuration file
 */
$di->setShared('db', function () 
{
    /*
    $config = $this->getConfig();

    $class = 'Phalcon\Db\Adapter\Pdo\\Mysql';
    
    
    $params = [
        'host'     => \config::database['host'],
        'username' => \config::database['username'],
        'password' => \config::database['password'],
        'dbname'   => \config::database['dbname'],
        'charset'  => \config::database['charset']
    ];


    $connection = new $class($params);

    return $connection;*/
    
    return baseModel::DB()->get_conn();
    
});


/**
 * If the configuration specify the use of metadata adapter use it or use memory otherwise
 */
$di->setShared('modelsMetadata', function () {
    return new MetaDataAdapter();
});

/**
 * Register the session flash service with the Twitter Bootstrap classes
 */



    //Registering a dispatcher
    $di->set('dispatcher', function() 
    {
        
        
        $eventsManager = new Manager();
        
        	
        
        $eventsManager->attach("dispatch", function($event, $dispatcher, $exception)
        {
                     
            if ($event->getType() == 'beforeException' )
            {
                $desc = array();
                $desc['文件'] = $exception->getFile ();
                $desc['行']  =  $exception->getLine();
                $desc['错误'] = $exception->getMessage();
                
         
                /*
                $o = array();
                $status = array();
                $status['succeed'] = 0;
                $status['error_code'] = -1;
                $status['error_desc'] = $exception->getMessage();;
                $status['debug'] =  $desc;
                $o['status'] = $status;
                die(json_encode($o));*/
        
                
                
                
                App\Lib\System\App::Input()->error($desc);
        
                
            }
        
        });
        
       
        
        
        
        $dispatcher = new \Phalcon\Mvc\Dispatcher();
        $dispatcher->setDefaultNamespace('App\Controllers');
        
        $dispatcher->setEventsManager($eventsManager);
        
        return $dispatcher;
    });
    
    
    $di->set(
        "session",
        function () {
            $session = new SessionAdapter();
    
            $session->start();
    
            return $session;
        }
        );


