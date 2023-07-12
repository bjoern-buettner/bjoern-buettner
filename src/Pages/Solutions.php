<?php

declare(strict_types=1);

namespace Me\BjoernBuettner\Pages;

use Me\BjoernBuettner\HTMLBuilder;

class Solutions
{
    public function __construct(private readonly HTMLBuilder $twig)
    {
    }
    public function get(string $lang): string
    {
        switch ($lang) {
            case 'en':
                return $this->twig->renderMinified('solutions-en.twig', [
                    'title' => 'Used Solutions',
                    'active' => '/solutions',
                    'description' => 'Björn Büttner\'s used solutions for providing the listed services',
                    'content' => [
                        'title' => 'Used Solutions',
                    ],
                ], $lang);
            case 'de':
            default:
                return $this->twig->renderMinified('solutions-de.twig', [
                    'title' => 'Genutzte Lösungen',
                    'active' => '/solutions',
                    'description' => 'Björn Büttners genutzte Lösungen zur Bereitstellung der aufgeführten Dienste',
                    'content' => [
                        'title' => 'Genutzte Lösungen',
                    ],
                ], $lang);
        }
    }
}
