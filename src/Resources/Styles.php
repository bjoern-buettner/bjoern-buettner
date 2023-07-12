<?php

declare(strict_types=1);

namespace Me\BjoernBuettner\Resources;

use Me\BjoernBuettner\Cache;
use ScssPhp\ScssPhp\Compiler;

class Styles
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
        $fullfile = dirname(__DIR__, 2) . '/resources/' . $file . '.scss';
        if (!is_file($fullfile)) {
            header('HTTP/1.1 404 Not Found', true, 404)   ;
            return '404 Not Found';
        }
        header('Content-Type: text/css; charset=utf-8', true, 200);
        $cache = md5($file) . md5((string) filemtime($fullfile)) . '.css';
        if ($data = $this->cache->get($cache)) {
            return $data;
        }
        $scss = new Compiler();
        $scss->setOutputStyle('compressed');
        if (DIRECTORY_SEPARATOR === '\\') {
            $fullfile = str_replace('\\', '/', $fullfile);
        }
        $data = $scss
            ->compileString('@import("' . $fullfile . '")')
            ->getCss();
        $this->cache->set($cache, $data);
        return $data;
    }
}
