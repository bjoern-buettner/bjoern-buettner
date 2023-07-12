<?php

declare(strict_types=1);

namespace Me\BjoernBuettner\Pages\Admin;

use Me\BjoernBuettner\TextOutputBuilder;

class Profile
{
    public function __construct(private readonly TextOutputBuilder $twig)
    {
    }
    public function get(string $lang): string
    {
        return $this->twig->renderHTML('admin/self.twig', [
            'title' => 'Max Mustermann',
            'active' => '/admin/self',
            'description' => 'Short self description',
        ], $lang);
    }
}
