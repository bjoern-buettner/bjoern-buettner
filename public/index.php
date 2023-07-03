<?php

use Dotenv\Dotenv;
use Me\BjoernBuettner\Application;
use Me\BjoernBuettner\Pages\Blog;
use Me\BjoernBuettner\Pages\Home;
use Me\BjoernBuettner\Pages\Imprint;
use Me\BjoernBuettner\Pages\Prices;
use Me\BjoernBuettner\Pages\Solutions;
use Me\BjoernBuettner\Resources\Styles;

require_once (__DIR__ . '/../vendor/autoload.php');

Dotenv::createImmutable(dirname(__DIR__))->load();

echo (new Application())
    ->res('/me.jpg', function (): string {
        header('Content-Type: image/jpeg', true);
        return file_get_contents(__DIR__ . '/../resources/me.jpg');
    })
    ->res('/de.jpg', function (): string {
        header('Content-Type: image/jpeg', true);
        return file_get_contents(__DIR__ . '/../resources/de.jpg');
    })
    ->res('/en.jpg', function (): string {
        header('Content-Type: image/jpeg', true);
        return file_get_contents(__DIR__ . '/../resources/en.jpg');
    })
    ->res('/logo.png', function (): string {
        header('Content-Type: image/png', true);
        return file_get_contents(__DIR__ . '/../resources/logo.png');
    })
    ->res('/icon.png', function (): string {
        header('Content-Type: image/png', true);
        return file_get_contents(__DIR__ . '/../resources/icon.png');
    })
    ->res('/favicon.ico', function (): string {
        header('Content-Type: image/x-icon', true);
        return file_get_contents(__DIR__ . '/../resources/favicon.ico');
    })
    ->res('/cookieinfo.js', function (): string {
        header('Content-Type: application/javascript', true);
        return file_get_contents(__DIR__ . '/../resources/cookieinfo.js');
    })
    ->res('/hosted-by-bjoern-buettner.js', function (): string {
        header('Content-Type: application/javascript', true);
        return file_get_contents(__DIR__ . '/../resources/hosted-by-bjoern-buettner.js');
    })
    ->res('/hosted-by-bjoern-buettner.css', function (): string {
        header('Content-Type: text/css', true);
        return file_get_contents(__DIR__ . '/../resources/hosted-by-bjoern-buettner.css');
    })
    ->res('/sitemap.xml', function (): string {
        header('Content-Type: text/xml', true);
        return file_get_contents(__DIR__ . '/../resources/sitemap.xml');
    })
    ->res('/static_sitemap_{lang:en|de}.xml', function (string $lang): string {
        header('Content-Type: text/xml', true);
        return file_get_contents(__DIR__ . "/../resources/static_sitemap_$lang.xml");
    })
    ->res('/robots.txt', function (): string {
        header('Content-Type: text/plain', true);
        return file_get_contents(__DIR__ . '/../resources/robots.txt');
    })
    ->res('/styles.css', [Styles::class, 'get'])
    ->get('/', [Home::class, 'get'])
    ->get('/imprint', [Imprint::class, 'get'])
    ->get('/prices', [Prices::class, 'get'])
    ->get('/solutions', [Solutions::class, 'get'])
    ->get('/blog', [Blog::class, 'get'])
    ->get('/blog/{slug}', [Blog::class, 'detail'])
    ->res('/blog_sitemap_{lang:de|en}.xml', [Blog::class, 'sitemap'])
    ->res('/rss.xml', [Blog::class, 'rss'])
    ->run();
