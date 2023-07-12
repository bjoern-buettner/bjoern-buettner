<?php

declare(strict_types=1);

namespace Me\BjoernBuettner\Pages\Admin;

use Me\BjoernBuettner\TwigWrapper;

class Quote
{
    public function __construct(private readonly TwigWrapper $twig)
    {
    }
    public function get(string $lang): string
    {
        return $this->twig->renderMinified('admin/quote.twig', [
            'title' => 'Quote 2023BB0001',
            'active' => '/admin/quote/1',
            'description' => 'Short quote description',
        ], $lang);
    }
}
