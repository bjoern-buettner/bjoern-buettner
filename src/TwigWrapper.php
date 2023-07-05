<?php

namespace Me\BjoernBuettner;

use Exception;
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
        return $this->lang . md5(filemtime(dirname(__DIR__) . '/templates/' . $template)) . md5($template) . sha1(json_encode($context));
    }

    public function render($template, array $context = []): string
    {
        $cache = dirname(__DIR__) . '/cache/' . $this->getCacheKey($template, $context) . '.min.twig';
        if (is_file($cache)) {
            return file_get_contents($cache);
        }
        $data = $this->renderUnminified($template, $context);
        try {
            $htmlMin = new HtmlMin();
            $data = $htmlMin->minify($data);
        } catch (Exception $e) {
            // ignore
        }
        file_get_contents($cache, $data);
        return $data;
    }

    public function renderUnminified(string $template, array $context = []): string
    {
        $cache = dirname(__DIR__) . '/cache/' . $this->getCacheKey($template, $context) . '.twig';
        if (is_file($cache)) {
            return file_get_contents($cache);
        }
        $context['lang'] = $this->lang;
        $context['menu'] = $this->lang === 'en' ? MenuList::$en : MenuList::$de;
        $context['og_type'] = $context['og_type'] ?? 'website';
        $context['author'] = $context['author'] ?? ['name' => 'Björn Büttner'];
        $data = parent::render($template, $context);
        file_put_contents($cache, $data);
        return $data;
    }
}
