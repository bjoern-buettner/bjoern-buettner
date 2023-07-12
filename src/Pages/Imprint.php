<?php

declare(strict_types=1);

namespace Me\BjoernBuettner\Pages;

use Me\BjoernBuettner\HTMLBuilder;

class Imprint
{
    public function __construct(private readonly HTMLBuilder $twig)
    {
    }

    public function get(string $lang): string
    {
        switch ($lang) {
            case 'en':
                return $this->twig->renderMinified('imprint-en.twig', [
                    'title' => 'Imprint',
                    'active' => '/imprint',
                    'description' => 'Björn Büttner\'s imprint, if you got any legal issues with the site or domain'
                        . 'this is where you want to go',
                ], $lang);
            case 'de':
            default:
                return $this->twig->renderMinified('imprint-de.twig', [
                    'title' => 'Impressum',
                    'active' => '/imprint',
                    'description' => 'Björn Büttners Impressum, bei rechtlichen Fragen zur Seite oder Domain'
                        . 'ist dies die richtige Anlaufstelle',
                ], $lang);
        }
    }
}
