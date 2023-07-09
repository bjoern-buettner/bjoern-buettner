<?php

declare(strict_types=1);

namespace Me\BjoernBuettner\Resources;

use Me\BjoernBuettner\Caches\Factory;
use ScssPhp\ScssPhp\Compiler;
use Twig\Environment;

class Styles
{
    public static function get(Environment $twig, string $lang, array $args): string
    {
        if (!isset($args['file']) || !preg_match('/^[a-z0-9-]+$/', $args['file'])) {
            header('HTTP/1.1 404 Not Found', true, 404)   ;
            return '404 Not Found';
        }
        $file = dirname(__DIR__, 2) . '/resources/' . $args['file'] . '.scss';
        if (!is_file($file)) {
            header('HTTP/1.1 404 Not Found', true, 404)   ;
            return '404 Not Found';
        }
        header('Content-Type: text/css; charset=utf-8', true, 200);
        $cache = md5($args['file']) . md5((string) filemtime($file)) . '.css';
        if ($data = Factory::get()->get($cache)) {
            return $data;
        }
        $scss = new Compiler();
        $scss->setOutputStyle('compressed');
        if (DIRECTORY_SEPARATOR === '\\') {
            $file = str_replace('\\', '/', $file);
        }
        $data = $scss
            ->compileString('@import("' . $file . '")')
            ->getCss();
        Factory::get()->set($cache, $data);
        return $data;
    }
}
