<?php

declare(strict_types=1);

namespace Me\BjoernBuettner\Pages;

use Twig\Environment;

class Imprint
{
    public static function get(Environment $twig, string $lang): string
    {
        switch ($lang) {
            case 'en':
                return $twig->render('imprint-en.twig', [
                    'title' => 'Imprint',
                    'active' => '/imprint',
                    'description' => 'Björn Büttner\'s imprint, if you got any legal issues with the site or domain'
                        . 'this is where you want to go',
                ]);
            case 'de':
            default:
                return $twig->render('imprint-de.twig', [
                    'title' => 'Impressum',
                    'active' => '/imprint',
                    'description' => 'Björn Büttners Impressum, bei rechtlichen Fragen zur Seite oder Domain'
                        . 'ist dies die richtige Anlaufstelle',
                ]);
        }
    }
}
