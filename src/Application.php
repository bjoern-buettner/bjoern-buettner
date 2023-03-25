<?php

namespace Me\BjoernBuettner;

use FastRoute\Dispatcher;
use FastRoute\RouteCollector;
use Teto\HTTP\AcceptLanguage;
use Twig\Loader\FilesystemLoader;
use function FastRoute\simpleDispatcher;

class Application
{
    private array $routes = [
        'GET' => [],
        'POST' => [],
    ];

    public function res(string $route, callable $func): self
    {
        $this->routes['GET'][$route] = $func;
        return $this;
    }
    public function get(string $route, callable $func): self
    {
        $subroute = rtrim($route, '/');
        $this->routes['GET'][$route] = function () use ($subroute): string {
            $lang = 'en';
            foreach(AcceptLanguage::get() as $language) {
                if ($language['language'] === 'de') {
                    $lang = 'de';
                    break;
                }
                if ($language['language'] === 'en') {
                    $lang = 'en';
                    break;
                }
            }
            header("Location: $subroute/$lang", true, 303);
            return '';
        };
        $this->routes['GET']["$subroute/{lang:en|de}"] = $func;
        return $this;
    }
    public function post(string $route, callable $func): self
    {
        $subroute = rtrim($route, '/');
        $this->routes['POST']["$subroute/{lang:en|de}"] = $func;
        return $this;
    }
    public function run(): string
    {
        $dispatcher = simpleDispatcher(function(RouteCollector $r) {
            foreach ($this->routes as $method => $routes) {
                foreach ($routes as $route => $func) {
                    $r->addRoute($method, $route, $func);
                }
            }
        });
        $uri = $_SERVER['REQUEST_URI'];
        if (false !== $pos = strpos($uri, '?')) {
            $uri = substr($uri, 0, $pos);
        }
        $routeInfo = $dispatcher->dispatch($_SERVER['REQUEST_METHOD'], rawurldecode($uri));
        switch ($routeInfo[0]) {
            case Dispatcher::NOT_FOUND:
                header('', true, 404);
                return "404 NOT FOUND";
            case Dispatcher::METHOD_NOT_ALLOWED:
                header('', true, 405);
                return "405 METHOD NOT ALLOWED";
            case Dispatcher::FOUND:
                $twig = new TwigWrapper(new FilesystemLoader(dirname(__DIR__) . '/templates'));
                $handler = $routeInfo[1];
                return $handler($twig, $routeInfo[2]['lang'] ?? '');
        }
    }
}
