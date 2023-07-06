<?php
declare(strict_types=1);

namespace Me\BjoernBuettner\Caches;

use Me\BjoernBuettner\Cache;

class Factory
{
    private static ?Cache $cache = null;
    public static function get(): Cache
    {
        if (self::$cache !== null) {
            return self::$cache;
        }
        if (extension_loaded('memcached') && $_ENV['USE_MEMCACHED'] === 'true') {
            return self::$cache = new Memcache();
        }
        return self::$cache = new File();
    }
}