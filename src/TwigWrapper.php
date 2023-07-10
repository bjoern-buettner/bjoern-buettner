<?php

declare(strict_types=1);

namespace Me\BjoernBuettner;

use Exception;
use Me\BjoernBuettner\Caches\Factory;
use Parsedown;
use Twig\Environment;
use Twig\TwigFilter;
use voku\helper\HtmlMin;

class TwigWrapper
{
    public function __construct(private Environment $twig)
    {
        $parsedown = new Parsedown();
        $twig->addFilter(new TwigFilter('markdown', function ($markdown) use ($parsedown) {
            return $parsedown->text($markdown);
        }, ['is_safe' => ['html']]));
        $twig->addFilter(new TwigFilter('fixurl', function ($url) {
            return str_replace(':/', '://', str_replace('//', '/', $url));
        }));
    }

    private function getCacheKey(string $template, array $context, string $lang): string
    {
        return $lang . md5(
            (string) filemtime(
                dirname(__DIR__) .
                DIRECTORY_SEPARATOR .
                'templates' .
                DIRECTORY_SEPARATOR .
                $template
            )
        ) . md5($template) . sha1(json_encode($context)) . '.twig';
    }

    public function renderMinified($template, array $context, string $lang): string
    {
        $cache = $this->getCacheKey($template, $context, $lang) . '.min';
        if ($data = Factory::get()->get($cache)) {
            return $data;
        }
        $data = $this->renderUnminified($template, $context, $lang);
        try {
            $htmlMin = new HtmlMin();
            $data = $htmlMin->minify($data);
        } catch (Exception $e) {
            // ignore
        }
        Factory::get()->set($cache, $data);
        return $data;
    }

    public function renderUnminified(string $template, array $context, string $lang): string
    {
        $cache = $this->getCacheKey($template, $context, $lang);
        if ($data = Factory::get()->get($cache)) {
            return $data;
        }
        $context['lang'] = $lang;
        $context['menu'] = $lang === 'en' ? MenuList::$en : MenuList::$de;
        $context['og_type'] = $context['og_type'] ?? 'website';
        $context['author'] = $context['author'] ?? ['name' => 'Björn Büttner'];
        $data = $this->twig->render($template, $context);
        Factory::get()->set($cache, $data);
        return $data;
    }
}
