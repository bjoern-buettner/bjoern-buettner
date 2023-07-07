<?php

declare(strict_types=1);

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
                    'description' => 'A warm welcome to our site and some basic information about bjoern-buettner.me',
                ]);
            case 'de':
            default:
                return $twig->render('home-de.twig', [
                    'title' => 'Start & Willkommen',
                    'active' => '/',
                    'description' => 'Ein herzliches willkommen auf unserer Seite'
                        . ' und ein par Grundinformationen über bjoern-buettner.me',
                ]);
        }
    }
}
