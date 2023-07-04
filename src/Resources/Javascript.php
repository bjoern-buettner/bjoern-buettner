<?php
declare(strict_types=1);

namespace Me\BjoernBuettner\Resources;

use MatthiasMullie\Minify\JS;
use Twig\Environment;

class Javascript
{
    public static function get(Environment $twig, string $lang, array $args): string
    {
        if (!isset($args['file']) || !preg_match('/^[a-z0-9-]+$/', $args['file'])) {
            header('HTTP/1.1 404 Not Found', true, 404)   ;
            return '404 Not Found';
        }
        $file = dirname(__DIR__, 2) . '/resources/' . $args['file'] . '.js';
        if (!is_file($file)) {
            header('HTTP/1.1 404 Not Found', true, 404)   ;
            return '404 Not Found';
        }
        header('Content-Type: text/javascript; charset=utf-8', true, 200);
        $js = file_get_contents(dirname(__DIR__, 2) . '/resources/' . $args['file'] . '.js') ?: '';
        $minifier = new JS();
        return $minifier->add($js)->minify();
    }
}