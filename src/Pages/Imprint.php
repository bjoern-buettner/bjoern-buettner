<?php

declare(strict_types=1);

namespace Me\BjoernBuettner\Pages;

use Me\BjoernBuettner\TextOutputBuilder;

class Imprint
{
    public function __construct(private readonly TextOutputBuilder $twig)
    {
    }

    public function get(string $lang): string
    {
        switch ($lang) {
            case 'en':
                return $this->twig->renderHTML('imprint-en.twig', [
                    'title' => 'Imprint',
                    'active' => '/imprint',
                    'description' => 'Björn Büttner\'s imprint, if you got any legal issues with the site or domain'
                        . 'this is where you want to go',
                ], $lang);
            case 'de':
            default:
                return $this->twig->renderHTML('imprint-de.twig', [
                    'title' => 'Impressum',
                    'active' => '/imprint',
                    'description' => 'Björn Büttners Impressum, bei rechtlichen Fragen zur Seite oder Domain'
                        . 'ist dies die richtige Anlaufstelle',
                ], $lang);
        }
    }
}
