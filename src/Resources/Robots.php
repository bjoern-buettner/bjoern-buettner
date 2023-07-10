<?php

declare(strict_types=1);

namespace Me\BjoernBuettner\Resources;

class Robots
{
    public function get(): string
    {
        header('Content-Type: text/plain', true, 200);
        return file_get_contents(dirname(__DIR__, 2) . '/resources/robots.txt');
    }
}
