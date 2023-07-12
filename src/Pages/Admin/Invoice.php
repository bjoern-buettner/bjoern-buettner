<?php

declare(strict_types=1);

namespace Me\BjoernBuettner\Pages\Admin;

use Me\BjoernBuettner\TextOutputBuilder;

class Invoice
{
    public function __construct(private readonly TextOutputBuilder $twig)
    {
    }
    public function get(string $lang): string
    {
        return $this->twig->renderMinified('admin/invoice.twig', [
            'title' => 'Invoice 2023BB0001',
            'active' => '/admin/invoice/1',
            'description' => 'Short invoice description',
        ], $lang);
    }
}
