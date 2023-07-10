<?php

use Dotenv\Dotenv;
use Me\BjoernBuettner\Application;
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
use Me\BjoernBuettner\TwigWrapper;
use Twig\Loader\FilesystemLoader;
use Twig\Loader\LoaderInterface;

require_once(__DIR__ . '/../vendor/autoload.php');

Dotenv::createImmutable(dirname(__DIR__))->load();

echo (new Application())
    ->param(FilesystemLoader::class, 'paths', __DIR__ . '/../templates')
    ->interface(LoaderInterface::class, FilesystemLoader::class)
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
#    ->get('/admin/blog', [AdminBlog::class, 'get'])
#    ->post('/admin/blog', [AdminBlog::class, 'post'])
#    ->get('/admin/blog/{slug}', [AdminBlog::class, 'detail'])
#    ->post('/admin/blog/{slug}', [AdminBlog::class, 'modify'])
#    ->get('/admin/team', [AdminTeam::class, 'get'])
#    ->post('/admin/team', [AdminTeam::class, 'post'])
#    ->get('/admin/team/{slug}', [AdminTeam::class, 'get'])
#    ->post('/admin/team/{slug}', [AdminTeam::class, 'post'])
#    ->get('/admin/prices', [AdminPrices::class, 'get'])
#    ->post('/admin/prices', [AdminPrices::class, 'post'])
#    ->get('/admin/prices/{id}', [AdminPrices::class, 'get'])
#    ->post('/admin/prices/{id}', [AdminPrices::class, 'post'])
#    ->get('/admin/user/{id}', [AdminUser::class, 'get'])
#    ->post('/admin/user/{id}', [AdminUser::class, 'post'])
#    ->get('/admin/invoice/{id}', [AdminInvoice::class, 'get'])
#    ->post('/admin/invoice/{id}', [AdminInvoice::class, 'post'])
#    ->get('/admin/quote/{id}', [AdminQuote::class, 'get'])
#    ->post('/admin/quote/{id}', [AdminQuote::class, 'post'])
#    ->get('/customer', [Customer::class, 'get'])
#    ->get('/customer/backup/{uuid}', [CustomerBackup::class, 'get'])
#    ->get('/customer/invoice/{id}', [CustomerInvoice::class, 'get'])
#    ->get('/customer/invoice/{id}/pdf', [CustomerInvoice::class, 'pdf'])
#    ->get('/customer/quote/{id}', [CustomerQuote::class, 'get'])
#    ->get('/customer/quote/{id}/pdf', [CustomerQuote::class, 'pdf'])
#    ->get('/customer/ticket/{id}', [CustomerTicket::class, 'get'])
#    ->get('/customer/ticket/{id}', [CustomerTicket::class, 'get'])
    ->get('/login', [Login::class, 'get'])
    ->post('/login', [Login::class, 'post'])
    ->run();
