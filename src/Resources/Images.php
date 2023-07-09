<?php

declare(strict_types=1);

namespace Me\BjoernBuettner\Resources;

use Twig\Environment;

class Images
{
    public static function get(Environment $twig, string $lang, array $args): string
    {
        if (!isset($args['file']) || !preg_match('/^[a-z0-9-]+$/', $args['file'])) {
            header('HTTP/1.1 404 Not Found', true, 404)   ;
            return '404 Not Found';
        }
        $file = dirname(__DIR__, 2) . '/resources/' . $args['file'] . '.' . $args['ext'];
        if (!is_file($file)) {
            header('HTTP/1.1 404 Not Found', true, 404)   ;
            return '404 Not Found';
        }
        header(
            'Content-Type: image/' . ($args['ext'] === 'jpg' ? 'jpeg' : $args['ext']),
            true,
            200
        );
        return file_get_contents($file) ?: '';
    }
}
