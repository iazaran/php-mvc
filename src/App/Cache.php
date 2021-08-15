<?php

namespace App;

use Memcached;

/**
 * Class Cache
 * @package App
 */
class Cache
{
    /**
     * Connect to Memcached
     *
     * @return bool|Memcached
     */
    private static function init(): bool|Memcached
    {
        if (MEMCACHED_ENABLED) {
            $memcached = new Memcached;
            $memcached->addServer(MEMCACHED_HOST, MEMCACHED_PORT);

            return $memcached;
        }

        return false;
    }

    /**
     * Checking cached data in a key by Memcached
     *
     * @param $key
     * @return mixed
     */
    public static function checkCache($key): mixed
    {
        if ($memcached = self::init()) {
            if ($memcached->get($key)) return $memcached->get($key);
        }

        return false;
    }

    /**
     * Caching data in a key by Memcached
     *
     * @param $key
     * @param $data
     * @return mixed
     */
    public static function cache($key, $data): mixed
    {
        if ($memcached = self::init()) {
            if ($memcached->get($key)) $memcached->delete($key);
            $memcached->set($key, $data, time() + CACHE_TIME_SEC);
        }

        return $data;
    }
}
