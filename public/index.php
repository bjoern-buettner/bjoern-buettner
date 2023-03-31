<?php

use Dotenv\Dotenv;
use Me\BjoernBuettner\Application;
use Me\BjoernBuettner\Pages\Contact;
use Me\BjoernBuettner\Pages\Dashboard;
use Me\BjoernBuettner\Pages\Home;
use Me\BjoernBuettner\Pages\Imprint;
use Me\BjoernBuettner\Pages\Login;
use Me\BjoernBuettner\Pages\Prices;
use Me\BjoernBuettner\Pages\Sent;
use Me\BjoernBuettner\Pages\Tickets;

require_once (__DIR__ . '/../vendor/autoload.php');

Dotenv::createImmutable(dirname(__DIR__))->load();

echo (new Application())
    ->res('/styles.css', function (): string {
        header('Content-Type: text/css', true);
        return file_get_contents(__DIR__ . '/../resources/styles.css');
    })
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
    ->get('/', [Home::class, 'get'])
    ->get('/contact', [Contact::class, 'get'])
    ->get('/imprint', [Imprint::class, 'get'])
    ->get('/prices', [Prices::class, 'get'])
    ->get('/dashboard', [Dashboard::class, 'get'])
    ->post('/tickets', [Tickets::class, 'post'])
    ->get('/tickets/{id}', [Tickets::class, 'get'])
    ->post('/tickets/{id}', [Tickets::class, 'detail'])
    ->get('/login', [Login::class, 'get'])
    ->post('/login', [Login::class, 'post'])
    ->post('/sent', [Sent::class, 'post'])
    ->run();