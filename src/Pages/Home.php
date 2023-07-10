<?php

declare(strict_types=1);

namespace Me\BjoernBuettner\Pages;

use Me\BjoernBuettner\TwigWrapper;

class Home
{
    public function __construct(private readonly TwigWrapper $twig)
    {
    }
    public function get(string $lang): string
    {
        switch ($lang) {
            case 'en':
                return $this->twig->renderMinified('home-en.twig', [
                    'title' => 'Home & Welcome',
                    'active' => '/',
                    'description' => 'A warm welcome to our site and some basic information about bjoern-buettner.me',
                ], $lang);
            case 'de':
            default:
                return $this->twig->renderMinified('home-de.twig', [
                    'title' => 'Start & Willkommen',
                    'active' => '/',
                    'description' => 'Ein herzliches willkommen auf unserer Seite'
                        . ' und ein par Grundinformationen über bjoern-buettner.me',
                ], $lang);
        }
    }
}
