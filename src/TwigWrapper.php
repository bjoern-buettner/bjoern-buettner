<?php

namespace Me\BjoernBuettner;

use Twig\Environment;
use voku\helper\HtmlMin;

class TwigWrapper extends Environment
{
    public function render($template, array $context = []): string
    {
        $data = parent::render($template, $context);
        $htmlMin = new HtmlMin();
        return $htmlMin->minify($data);
    }
}
