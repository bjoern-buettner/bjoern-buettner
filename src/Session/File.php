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
    private function getSessionFile(string $id): string
    {
        return dirname(__DIR__, 2) . '/cache/' . $this->getIPKey() . $id . '.session';
    }

    public function read(string $id): string|false
    {
        $file = $this->getSessionFile($id);
        if (is_file($file)) {
            return file_get_contents($file) ?: '';
        }
        return '';
    }

    public function write(string $id, string $data): bool
    {
        return strlen($data) === file_put_contents($this->getSessionFile($id), $data);
    }

    public function updateTimestamp(string $id, string $data): bool
    {
        return touch($this->getSessionFile($id));
    }
}
