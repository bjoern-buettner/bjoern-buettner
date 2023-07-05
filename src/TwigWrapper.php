<?php

namespace Me\BjoernBuettner;

use Exception;
use Parsedown;
use Twig\Environment;
use Twig\Loader\LoaderInterface;
use Twig\TemplateWrapper;
use Twig\TwigFilter;
use voku\helper\HtmlMin;

class TwigWrapper extends Environment
{
    private string $lang;

    public function __construct(LoaderInterface $loader, string $lang)
    {
        parent::__construct($loader);
        $this->lang = $lang;
        $parsedown = new Parsedown();
        $this->addFilter(new TwigFilter('markdown', function ($markdown) use ($parsedown) {
            return $parsedown->text($markdown);
        }, ['is_safe' => ['html']]));
    }

    public function render($template, array $context = []): string
    {
        $data = $this->renderUnminified($template, $context);
        try {
            $htmlMin = new HtmlMin();
            return $htmlMin->minify($data);
        } catch (Exception $e) {
            return $data;
        }
    }

    public function renderUnminified(string $template, array $context = []): string
    {
        $context['lang'] = $this->lang;
        $context['menu'] = $this->lang === 'en' ? MenuList::$en : MenuList::$de;
        $context['og_type'] = $context['og_type'] ?? 'website';
        $context['author'] = $context['author'] ?? ['name' => 'Björn Büttner'];
        return parent::render($template, $context);
    }
}
