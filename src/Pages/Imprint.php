<?php

namespace Me\BjoernBuettner\Pages;

use Me\BjoernBuettner\MenuList;
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
                    'lang' => 'en',
                    'description' => 'Björn Büttner\'s imprint',
                    'menu' => MenuList::$en,
                ]);
            case 'de':
                return $twig->render('imprint-de.twig', [
                    'title' => 'Impressum',
                    'active' => '/imprint',
                    'description' => 'Björn Büttners Impressum',
                    'lang' => 'de',
                    'menu' => MenuList::$de,
                ]);
        }
    }
}