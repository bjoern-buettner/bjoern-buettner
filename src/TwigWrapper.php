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
        $context['lang'] = $this->lang;
        $context['menu'] = $this->lang === 'en' ? MenuList::$en : MenuList::$de;
        $context['og_type'] = $context['og_type'] ?? 'website';
        $data = parent::render($template, $context);
        try {
            $htmlMin = new HtmlMin();
            return $htmlMin->minify($data);
        } catch (\Exception $e) {
            return $data;
        }
    }
}
