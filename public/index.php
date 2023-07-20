<?php

declare(strict_types=1);

use Dotenv\Dotenv;
use Me\BjoernBuettner\Application;
use Me\BjoernBuettner\Cache;
use Me\BjoernBuettner\Caches\Factory as CacheFactory;
use Me\BjoernBuettner\Pages\Blog;
use Me\BjoernBuettner\Pages\Home;
use Me\BjoernBuettner\Pages\Imprint;
use Me\BjoernBuettner\Pages\Login;
use Me\BjoernBuettner\Pages\Prices;
use Me\BjoernBuettner\Pages\Solutions;
use Me\BjoernBuettner\Pages\Team;
use Me\BjoernBuettner\Resources\Favicon;
use Me\BjoernBuettner\Resources\Images;
use Me\BjoernBuettner\Resources\Javascript;
use Me\BjoernBuettner\Resources\Robots;
use Me\BjoernBuettner\Resources\Sitemap;
use Me\BjoernBuettner\Resources\Styles;
use Me\BjoernBuettner\User;
use Me\BjoernBuettner\User\Factory as UserFactory;
use Twig\Loader\FilesystemLoader;
use Twig\Loader\LoaderInterface;
use Me\BjoernBuettner\Pages\Admin\Dashboard as AdminDashboard;
use Me\BjoernBuettner\Pages\Admin\Invoice as AdminInvoice;
use Me\BjoernBuettner\Pages\Admin\Quote as AdminQuote;
use Me\BjoernBuettner\Pages\Admin\Customer as AdminCustomer;
use Me\BjoernBuettner\Pages\Admin\Profile as AdminSelf;

require_once(__DIR__ . '/../vendor/autoload.php');

Dotenv::createImmutable(dirname(__DIR__))->load();

echo (new Application())
    ->param(FilesystemLoader::class, 'paths', __DIR__ . '/../templates')
    ->interface(LoaderInterface::class, FilesystemLoader::class)
    ->factory(Cache::class, CacheFactory::class)
    ->factory(User::class, UserFactory::class)
    ->res('/{file}.{ext:jpg|jpeg|png|gif}', [Images::class, 'get'])
    ->res('/favicon.ico', [Favicon::class, 'get'])
    ->res('/sitemap.xml', [Sitemap::class, 'get'])
    ->res('/static_sitemap_{lang:en|de}.xml', [Sitemap::class, 'get'])
    ->res('/robots.txt', [Robots::class, 'get'])
    ->res('/{file}.css', [Styles::class, 'get'])
    ->res('/{file}.js', [Javascript::class, 'get'])
    ->res('/blog_sitemap_{lang:de|en}.xml', [Blog::class, 'sitemap'])
    ->res('/rss-{lang:en|de}.xml', [Blog::class, 'rss'])
    ->res('/team/{slug}.jpg', [Team::class, 'image'])
    ->get('/', [Home::class, 'get'])
    ->get('/imprint', [Imprint::class, 'get'])
    ->get('/prices', [Prices::class, 'get'])
    ->get('/solutions', [Solutions::class, 'get'])
    ->get('/blog', [Blog::class, 'get'])
    ->get('/blog/{slug}', [Blog::class, 'detail'])
    ->get('/team', [Team::class, 'get'])
    ->get('/admin', [AdminDashboard::class, 'get'])
    ->get('/admin/customer/{id}', [AdminCustomer::class, 'get'])
    ->get('/admin/self', [AdminSelf::class, 'get'])
    ->get('/admin/invoice/{id}', [AdminInvoice::class, 'get'])
    ->get('/admin/quote/{id}', [AdminQuote::class, 'get'])
    ->get('/login', [Login::class, 'get'])
    ->post('/login', [Login::class, 'post'])
    ->run();
