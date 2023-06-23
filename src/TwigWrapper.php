<?php

namespace Me\BjoernBuettner;

use PDO;
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
        $context['lang'] = $this->lang;
        $context['menu'] = $this->lang === 'en' ? MenuList::$en : MenuList::$de;
        $context['google_site_verification'] = $_ENV['GOOGLE_SITE_VERIFICATION'];
        $data = parent::render($template, $context);
        $htmlMin = new HtmlMin();
        return $htmlMin->minify($data);
    }
}
