<?php

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
                    'description' => 'Björn Büttner\'s imprint',
                ]);
            case 'de':
                return $twig->render('imprint-de.twig', [
                    'title' => 'Impressum',
                    'active' => '/imprint',
                    'description' => 'Björn Büttners Impressum',
                ]);
        }
    }
}