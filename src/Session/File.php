<?php

declare(strict_types=1);

namespace Me\BjoernBuettner\Session;

class File extends Base
{
    public function close(): bool
    {
        return true;
    }

    public function destroy(string $id): bool
    {
        $file = dirname(__DIR__, 2) . '/cache/' . $this->getIPKey() . $id . '.session';
        if (!is_file($file)) {
            return true;
        }
        return unlink($file);
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
        $file = dirname(__DIR__, 2) . '/cache/' . $this->getIPKey() . $id . '.session';
        if (is_file($file)) {
            return file_get_contents($file);
        }
        return false;
    }

    public function write(string $id, string $data): bool
    {
        $file = dirname(__DIR__, 2) . '/cache/' . $this->getIPKey() . $id . '.session';
        return strlen($data) === file_put_contents($file, $data);
    }

    public function updateTimestamp(string $id, string $data): bool
    {
        return touch(dirname(__DIR__, 2) . '/cache/' . $this->getIPKey() . $id . '.session');
    }
}
