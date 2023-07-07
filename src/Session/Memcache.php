<?php

declare(strict_types=1);

namespace Me\BjoernBuettner\Session;

use Memcached;

class Memcache extends Base
{
    private Memcached $memcached;
    public function __construct()
    {
        $this->memcached = new Memcached();
    }
    public function close(): bool
    {
        return true;
    }

    public function destroy(string $id): bool
    {
        return $this->memcached->delete($this->getIPKey() . $id);
    }

    public function gc(int $max_lifetime): int|false
    {
        return 0;
    }

    public function open(string $path, string $name): bool
    {
        return true;
    }

    public function read(string $id): string|false
    {
        return $this->memcached->get($this->getIPKey() . $id);
    }

    public function write(string $id, string $data): bool
    {
        return $this->memcached->set($this->getIPKey() . $id, $data, 7200);
    }

    public function updateTimestamp(string $id, string $data): bool
    {
        $this->memcached->touch($this->getIPKey() . $id, 7200);
        return true;
    }
}
