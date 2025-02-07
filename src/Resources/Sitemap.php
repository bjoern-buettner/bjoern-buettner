<?php

declare(strict_types=1);

namespace Me\BjoernBuettner\Resources;

class Sitemap
{
    public static function get(string $lang = ''): string
    {
        header('Content-Type: text/xml; charset=utf-8', true, 200);
        if ($lang) {
            return file_get_contents(dirname(__DIR__, 2) . "/resources/static_sitemap_$lang.xml");
        }
        return file_get_contents(dirname(__DIR__, 2) . '/resources/sitemap.xml');
    }
}
