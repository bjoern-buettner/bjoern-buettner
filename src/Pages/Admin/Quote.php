<?php

declare(strict_types=1);

namespace Me\BjoernBuettner\Pages\Admin;

use Me\BjoernBuettner\TextOutputBuilder;

class Quote
{
    public function __construct(private readonly TextOutputBuilder $twig)
    {
    }
    public function get(string $lang): string
    {
        return $this->twig->renderHTML('admin/quote.twig', [
            'title' => 'Quote 2023BB0001',
            'active' => '/admin/quote/1',
            'description' => 'Short quote description',
        ], $lang);
    }
}
