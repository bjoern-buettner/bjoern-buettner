<?php

declare(strict_types=1);

namespace Me\BjoernBuettner\Pages\Admin;

use Me\BjoernBuettner\TwigWrapper;

class Cases
{
    public function __construct(private readonly TwigWrapper $twig)
    {
    }
    public function get(string $lang): string
    {
        return $this->twig->renderMinified('admin/case.twig', [
            'title' => 'Case #1',
            'active' => '/admin/case/1',
            'description' => 'Short case description',
        ], $lang);
    }
}
