<?php

declare(strict_types=1);

namespace Me\BjoernBuettner\Pages\Admin;

use Me\BjoernBuettner\TextOutputBuilder;

class Customer
{
    public function __construct(private readonly TextOutputBuilder $twig)
    {
    }
    public function get(string $lang): string
    {
        return $this->twig->renderHTML('admin/customer.twig', [
            'title' => 'Customer #1',
            'active' => '/admin/customer/1',
            'description' => 'Short customer description',
        ], $lang);
    }
}
