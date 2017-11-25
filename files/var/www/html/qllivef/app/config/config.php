<?php

$http_host = $_SERVER['HTTP_HOST'];

if ($http_host == 'myapi.chumao.net') {

    include "config_dev.php";
//    include "config_online.php";
//    include "config_youmei.php";

} elseif ($http_host == 'qlapi.miyintech.com') {

    include "config_online.php";

} elseif ($http_host == 'ymapi.miyintech.com') {

    include "config_youmei.php";

} else {

    include "config_dev.php";

}

