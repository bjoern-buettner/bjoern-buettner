<?php

declare(strict_types=1);

namespace Me\BjoernBuettner\Caches;

class NoCache implements \Me\BjoernBuettner\Cache
{
    public function get(string $key): ?string
    {
        return null;
    }

    public function set(string $key, string $value): void
    {
    }
}
