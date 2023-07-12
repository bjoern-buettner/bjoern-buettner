<?php

declare(strict_types=1);

namespace Me\BjoernBuettner\Pages\Admin;

use Me\BjoernBuettner\TwigWrapper;

class Customer
{
    public function __construct(private readonly TwigWrapper $twig)
    {
    }
    public function get(string $lang): string
    {
        return $this->twig->renderMinified('admin/customer.twig', [
            'title' => 'Customer #1',
            'active' => '/admin/customer/1',
            'description' => 'Short customer description',
        ], $lang);
    }
}
