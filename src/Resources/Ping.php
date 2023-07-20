<?php

declare(strict_types=1);

namespace Me\BjoernBuettner\Resources;

class Ping
{
    public function get(): string
    {
        header('Content-Type: text/plain', true, ($_SESSION['user'] ?? false) ? 202 : 403);
        return '';
    }
}
