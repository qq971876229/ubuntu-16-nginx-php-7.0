<?php

namespace bcl\redis;

use bcl\exception\bclException;
use bcl;

class cacheBase extends base
{


    public static function get($key, $fun, $ttl = 0)
    {

        $rd = self::get_rd();
        $data = $rd->get($key);

        if ($data != false) {
            return json_decode($data);
        }

        $data = $fun();


        if ($data === false && is_array($data) == false) {
            return false;
        }


        if ($ttl == 0) {
            $rd->set($key, json_encode($data));
        } else {
            $rd->set($key, json_encode($data), $ttl);
        }


        return $data;
    }

    public static function redis_get($key)
    {
        $rd = self::get_rd();
        $data = $rd->get($key);

        if ($data != false) {
            return json_decode($data);
        } else {
            return $data;
        }

    }

    public static function redis_set($key, $data, $expire=0)
    {
        $rd = self::get_rd();

        if ($expire === 0) {
            $result = $rd->set($key, json_encode($data));
        } else {
            $result = $rd->set($key, json_encode($data), $expire);
        }

        return $result;

    }


}