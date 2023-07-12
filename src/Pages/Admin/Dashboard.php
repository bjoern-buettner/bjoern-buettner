<?php

declare(strict_types=1);

namespace Me\BjoernBuettner\Pages\Admin;

use Me\BjoernBuettner\TextOutputBuilder;

class Dashboard
{
    public function __construct(private readonly TextOutputBuilder $twig)
    {
    }
    public function get(string $lang): string
    {
        return $this->twig->renderMinified('admin/dashboard.twig', [
            'title' => 'Dashboard',
            'active' => '/admin',
            'description' => 'Dashboard here',
        ], $lang);
    }
}
