<?php

declare(strict_types=1);

namespace Me\BjoernBuettner\Resources;

use Twig\Environment;

class Images
{
    public function get(string $file, string $ext): string
    {
        if (!preg_match('/^[a-z0-9-]+$/', $file)) {
            header('HTTP/1.1 404 Not Found', true, 404)   ;
            return '404 Not Found';
        }
        $fullfile = dirname(__DIR__, 2) . '/resources/' . $file . '.' . $ext;
        if (!is_file($fullfile)) {
            header('HTTP/1.1 404 Not Found', true, 404)   ;
            return '404 Not Found';
        }
        header(
            'Content-Type: image/' . ($ext === 'jpg' ? 'jpeg' : $ext),
            true,
            200
        );
        return file_get_contents($fullfile) ?: '';
    }
}
