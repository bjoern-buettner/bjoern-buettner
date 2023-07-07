<?php

namespace Me\BjoernBuettner;

use Exception;
use Me\BjoernBuettner\Caches\Factory;
use Parsedown;
use Twig\Environment;
use Twig\Loader\LoaderInterface;
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
        $this->addFilter(new TwigFilter('fixurl', function ($url) {
            return str_replace(':/', '://', str_replace('//', '/', $url));
        }));
    }

    private function getCacheKey(string $template, array $context): string
    {
        return $this->lang
            . md5((string) filemtime(dirname(__DIR__) . '/templates/' . $template))
            . md5($template)
            . sha1(json_encode($context));
    }

    public function render($template, array $context = []): string
    {
        $cache = $this->getCacheKey($template, $context) . '.min.twig';
        if ($data = Factory::get()->get($cache)) {
            return $data;
        }
        $data = $this->renderUnminified($template, $context);
        try {
            $htmlMin = new HtmlMin();
            $data = $htmlMin->minify($data);
        } catch (Exception $e) {
            // ignore
        }
        Factory::get()->set($cache, $data);
        return $data;
    }

    public function renderUnminified(string $template, array $context = []): string
    {
        $cache = $this->getCacheKey($template, $context) . '.twig';
        if ($data = Factory::get()->get($cache)) {
            return $data;
        }
        $context['lang'] = $this->lang;
        $context['menu'] = $this->lang === 'en' ? MenuList::$en : MenuList::$de;
        $context['og_type'] = $context['og_type'] ?? 'website';
        $context['author'] = $context['author'] ?? ['name' => 'Björn Büttner'];
        $data = parent::render($template, $context);
        Factory::get()->set($cache, $data);
        return $data;
    }
}
