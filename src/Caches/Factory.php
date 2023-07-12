<?php

declare(strict_types=1);

namespace Me\BjoernBuettner\Caches;

use Me\BjoernBuettner\Cache;

class Factory
{
    public static function get(): Cache
    {
        if (isset($_SESSION['user']) && $_SESSION['user']->active()) {
            return new NoCache();
        }
        if (($_ENV['ENABLE_CACHE'] ?? 'true') === 'false') {
            return new NoCache();
        }
        if (extension_loaded('memcached') && ($_ENV['ENABLE_MEMCACHED'] ?? 'false') === 'true') {
            return new Memcache();
        }
        return new File();
    }
}
