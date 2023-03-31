<?php

namespace Me\BjoernBuettner;

use Twig\Environment;
use Twig\Loader\LoaderInterface;
use voku\helper\HtmlMin;

class TwigWrapper extends Environment
{
    private string $lang;

    public function __construct(LoaderInterface $loader, string $lang)
    {
        parent::__construct($loader);
        $this->lang = $lang;
    }

    public function render($template, array $context = []): string
    {
        $context['loggedIn'] = isset($_SESSION['aid']);
        $context['lang'] = $this->lang;
        $context['menu'] = $this->lang === 'en' ? MenuList::$en : MenuList::$de;
        $data = parent::render($template, $context);
        $htmlMin = new HtmlMin();
        return $htmlMin->minify($data);
    }
}
