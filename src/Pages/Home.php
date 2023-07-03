<?php

namespace Me\BjoernBuettner\Pages;

use Twig\Environment;

class Home
{
    public static function get(Environment $twig, string $lang): string
    {
        switch ($lang) {
            case 'en':
                return $twig->render('home-en.twig', [
                    'title' => 'Home & Welcome',
                    'active' => '/',
                    'description' => 'A bit about Björn Büttner, the founder of bjoern-buettner.me',
                ]);
            case 'de':
            default:
                return $twig->render('home-de.twig', [
                    'title' => 'Start & Willkommen',
                    'active' => '/',
                    'description' => 'Ein wenig über Björn Büttner, den Gründer von bjoern-buettner.me',
                ]);
        }
    }
}
