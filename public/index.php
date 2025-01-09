<?php

declare(strict_types=1);

use Dotenv\Dotenv;
use Me\BjoernBuettner\Application;
use Me\BjoernBuettner\Cache;
use Me\BjoernBuettner\Caches\Factory as CacheFactory;
use Me\BjoernBuettner\Pages\Home;
use Me\BjoernBuettner\Pages\Imprint;
use Me\BjoernBuettner\Pages\Prices;
use Me\BjoernBuettner\Pages\Solutions;
use Me\BjoernBuettner\Resources\Favicon;
use Me\BjoernBuettner\Resources\Images;
use Me\BjoernBuettner\Resources\Javascript;
use Me\BjoernBuettner\Resources\Robots;
use Me\BjoernBuettner\Resources\Sitemap;
use Me\BjoernBuettner\Resources\Styles;
use Twig\Loader\FilesystemLoader;
use Twig\Loader\LoaderInterface;

require_once(__DIR__ . '/../vendor/autoload.php');

Dotenv::createImmutable(dirname(__DIR__))->load();

echo (new Application())
    ->param(FilesystemLoader::class, 'paths', __DIR__ . '/../templates')
    ->interface(LoaderInterface::class, FilesystemLoader::class)
    ->factory(Cache::class, CacheFactory::class)
    ->res('/{file}.{ext:jpg|jpeg|png|gif}', [Images::class, 'get'])
    ->res('/favicon.ico', [Favicon::class, 'get'])
    ->res('/sitemap.xml', [Sitemap::class, 'get'])
    ->res('/static_sitemap_{lang:en|de}.xml', [Sitemap::class, 'get'])
    ->res('/robots.txt', [Robots::class, 'get'])
    ->res('/{file}.css', [Styles::class, 'get'])
    ->res('/{file}.js', [Javascript::class, 'get'])
    ->get('/', [Home::class, 'get'])
    ->get('/imprint', [Imprint::class, 'get'])
    ->get('/prices', [Prices::class, 'get'])
    ->get('/solutions', [Solutions::class, 'get'])
    ->run();
