<?php

declare(strict_types=1);

namespace Me\BjoernBuettner\Pages\Admin;

use Me\BjoernBuettner\TextOutputBuilder;

class Cases
{
    public function __construct(private readonly TextOutputBuilder $twig)
    {
    }
    public function get(string $lang): string
    {
        return $this->twig->renderHTML('admin/case.twig', [
            'title' => 'Case #1',
            'active' => '/admin/case/1',
            'description' => 'Short case description',
        ], $lang);
    }
}
