<?php
declare(strict_types=1);

namespace Me\BjoernBuettner\Resources;

use ScssPhp\ScssPhp\Compiler;
use Twig\Environment;

class Styles
{
    public static function get(Environment $twig, string $lang): string
    {
        header('Content-Type: text/css; charset=utf-8', true, 200);
        $scss = new Compiler(['cacheDir' => __DIR__ . '/../../cache', 'prefix' => 'scss_']);
        $scss->setOutputStyle('compressed');
        return $scss->compileString('@import("' . dirname(__DIR__, 2) . '/resources/styles.scss")')->getCss();
    }
}