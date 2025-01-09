<?php

declare(strict_types=1);

namespace Me\BjoernBuettner\Pages;

use Me\BjoernBuettner\TextOutputBuilder;

class Home
{
    public function __construct(private readonly TextOutputBuilder $twig)
    {
    }
    public function get(string $lang): string
    {
        switch ($lang) {
            case 'en':
                return $this->twig->renderHTML('home-en.twig', [
                    'title' => 'Home & Welcome',
                    'active' => '/',
                    'description' => 'A warm welcome to our site and some basic information about bjoern-buettner.me',
                ], $lang);
            case 'de':
            default:
                return $this->twig->renderHTML('home-de.twig', [
                    'title' => 'Start & Willkommen',
                    'active' => '/',
                    'description' => 'Ein herzliches Willkommen auf unserer Seite'
                        . ' und ein par Grundinformationen über bjoern-buettner.me',
                ], $lang);
        }
    }
}
