<?php

namespace qcloudcos;

class Conf {
    // Cos php sdk version number.
    const VERSION = 'v4.2.0';
    const API_COSAPI_END_POINT = 'http://region.file.myqcloud.com/files/v2/';

    // Please refer to http://console.qcloud.com/cos to fetch your app_id, secret_id and secret_key.

    

    const APP_ID = \config::app['img_app_id'];
    
    const SECRET_ID = \config::app['SecretId'];
    
    const SECRET_KEY = \config::app['SecretKey'];
    
    const BUCKET = \config::app['img_bucket'];

    /**
     * Get the User-Agent string to send to COS server.
     */
    public static function getUserAgent() {
        return 'cos-php-sdk-' . self::VERSION;
    }
}
