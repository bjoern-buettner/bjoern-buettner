<?php
declare(strict_types=1);

namespace Me\BjoernBuettner\Session;

class Factory
{
    public static function start(bool $isWriteable): void
    {
        $hasMemcached = extension_loaded('memcached') && ($_ENV['ENABLE_MEMCACHED'] ?? 'false') === 'true';
        session_set_save_handler(
            $hasMemcached ? new Memcache() : new File(),
            true
        );
        session_set_cookie_params([
            'lifetime' => 7200,
            'path' => '/',
            'domain' => $_SERVER['HTTP_HOST'],
            'secure' => true,
            'httponly' => true,
            'samesite' => 'Strict',
        ]);
        session_start([
            'read_and_close' => !$isWriteable,
        ]);
    }
}
