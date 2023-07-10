<?php

declare(strict_types=1);

namespace Me\BjoernBuettner\Resources;

class Favicon
{
    public function get(): string
    {
        header('Content-Type: image/x-icon', true, 200);
        return file_get_contents(dirname(__DIR__, 2) . '/resources/favicon.ico');
    }
}
