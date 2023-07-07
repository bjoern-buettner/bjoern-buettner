<?php
declare(strict_types=1);

namespace Me\BjoernBuettner\Caches;

use Me\BjoernBuettner\Cache;
use Memcached;

class Memcache implements Cache
{
    private Memcached $cache;
    public function __construct()
    {
        $this->cache = new Memcached();
    }

    public function get(string $key): ?string
    {
        return $this->cache->get($key) ?: null;
    }

    public function set(string $key, string $value): void
    {
        $this->cache->set($key, $value);
    }

    public function __destruct(): void
    {
        $this->cache->quit();
    }
}
