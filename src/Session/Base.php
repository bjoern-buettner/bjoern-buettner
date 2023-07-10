<?php

declare(strict_types=1);

namespace Me\BjoernBuettner\Session;

use SessionHandlerInterface;
use SessionIdInterface;
use SessionUpdateTimestampHandlerInterface;

abstract class Base implements SessionHandlerInterface, SessionIdInterface, SessionUpdateTimestampHandlerInterface
{
    private string $prefix = '';

    // phpcs:ignore
    public function create_sid(): string
    {
        return substr(str_pad(base_convert((string) random_int(0, PHP_INT_MAX), 10, 32), 128, '0'), 0, 128);
    }

    final protected function getIPKey(): string
    {
        if ($this->prefix === '') {
            $this->prefix = md5($_SERVER['REMOTE_ADDR'] ?? '');
        }
        return $this->prefix;
    }

    public function validateId(string $id): bool
    {
        return strlen($id) === 128 && preg_match('/^[a-z0-9]+$/', $id);
    }
}
