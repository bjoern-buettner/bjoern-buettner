<?php

declare(strict_types=1);

namespace Me\BjoernBuettner\Caches;

use Me\BjoernBuettner\Cache;

class File implements Cache
{
    public function get(string $key): ?string
    {
        $cachefile = __DIR__ . '/../../cache/' . $key;
        if (!is_file($cachefile)) {
            return null;
        }
        return file_get_contents($cachefile) ?: null;
    }

    public function set(string $key, string $value): void
    {
        file_put_contents(__DIR__ . '/../../cache/' . $key, $value);
    }
}
