<?php

namespace Me\BjoernBuettner\Pages;

use Twig\Environment;

class Solutions
{
    public static function get(Environment $twig, string $lang): string
    {
        switch ($lang) {
            case 'en':
                return $twig->render('solutions-en.twig', [
                    'title' => 'Used Solutions',
                    'active' => '/solutions',
                    'description' => 'Björn Büttner\'s used solutions for providing the listed services',
                    'content' => [
                        'title' => 'Used Solutions',
                    ],
                ]);
            case 'de':
            default:
                return $twig->render('solutions-de.twig', [
                    'title' => 'Genutzte Lösungen',
                    'active' => '/solutions',
                    'description' => 'Björn Büttners genutzte Lösungen zur Bereitstellung der aufgeführten Dienste',
                    'content' => [
                        'title' => 'Genutzte Lösungen',
                    ],
                ]);
        }
    }
}