<?php

declare(strict_types=1);

namespace Me\BjoernBuettner\Resources;

use MatthiasMullie\Minify\JS;
use Me\BjoernBuettner\Cache;

class Javascript
{
    public function __construct(private readonly Cache $cache)
    {
    }
    public function get(string $file): string
    {
        if (!preg_match('/^[a-z0-9-]+$/', $file)) {
            header('HTTP/1.1 404 Not Found', true, 404)   ;
            return '404 Not Found';
        }
        $fullfile = dirname(__DIR__, 2) . '/resources/' . $file . '.js';
        if (!is_file($fullfile)) {
            header('HTTP/1.1 404 Not Found', true, 404)   ;
            return '404 Not Found';
        }
        header('Content-Type: text/javascript; charset=utf-8', true, 200);
        $cache = md5($file) . md5((string) filemtime($fullfile)) . '.min.js';
        if ($data = $this->cache->get($cache)) {
            return $data;
        }
        $js = file_get_contents($fullfile) ?: '';
        $minifier = new JS();
        $data = $minifier->add($js)->minify();
        $this->cache->set($cache, $data);
        return $data;
    }
}
