<?php
declare(strict_types=1);

namespace Me\BjoernBuettner\Resources;

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
        $scss = new Compiler(['cacheDir' => __DIR__ . '/../../cache', 'prefix' => 'scss_']);
        $scss->setOutputStyle('compressed');
        $file = dirname(__DIR__, 2) . '/resources/' . $args['file'] . '.scss';
        if (DIRECTORY_SEPARATOR === '\\') {
            $file = str_replace('\\', '/', $file);
        }
        return $scss
            ->compileString('@import("' . $file . '")')
            ->getCss();
    }
}