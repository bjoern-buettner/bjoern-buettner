<?php

declare(strict_types=1);

namespace Me\BjoernBuettner;

use FastRoute\Dispatcher;
use FastRoute\RouteCollector;
use Me\BjoernBuettner\DependencyInjector\DependencyBuilder;
use Me\BjoernBuettner\DependencyInjector\DTOs\FactoryMap;
use Me\BjoernBuettner\DependencyInjector\DTOs\InterfaceMap;
use Me\BjoernBuettner\DependencyInjector\DTOs\ParameterMap;
use Teto\HTTP\AcceptLanguage;

use Throwable;
use function FastRoute\simpleDispatcher;

class Application
{
    private array $routes = [
        'GET' => [],
        'POST' => [],
    ];
    private array $dependencies = [];

    public function interface(string $interface, string $class): self
    {
        $this->dependencies[] = new InterfaceMap($interface, $class);
        return $this;
    }
    public function factory(string $interface, string $class): self
    {
        $this->dependencies[] = new FactoryMap($interface, $class, 'get');
        return $this;
    }
    public function param(string $class, string $param, mixed $value): self
    {
        $this->dependencies[] = new ParameterMap($param, $class, $value);
        return $this;
    }
    public function res(string $route, array $func): self
    {
        $this->routes['GET'][$route] = $func;
        return $this;
    }
    public function get(string $route, array $func): self
    {
        $subroute = rtrim($route, '/');
        $this->routes['GET'][$route] = function () use ($subroute): string {
            $lang = 'en';
            foreach (AcceptLanguage::get() as $language) {
                if ($language['language'] === 'de') {
                    $lang = 'de';
                    break;
                }
            }
            header("Location: $subroute/$lang", true, 303);
            return '';
        };
        $this->routes['GET']["$subroute/{lang:en|de}"] = $func;
        return $this;
    }
    public function post(string $route, array $func): self
    {
        $subroute = rtrim($route, '/');
        $this->routes['POST']["$subroute/{lang:en|de}"] = $func;
        return $this;
    }
    public function run(): string
    {
        $dispatcher = simpleDispatcher(
            function (RouteCollector $r) {
                foreach ($this->routes as $method => $routes) {
                    foreach ($routes as $route => $func) {
                        $r->addRoute($method, $route, $func);
                    }
                }
            }
        );
        $uri = $_SERVER['REQUEST_URI'];
        if (false !== $pos = strpos($uri, '?')) {
            $uri = substr($uri, 0, $pos);
        }
        $routeInfo = $dispatcher->dispatch($_SERVER['REQUEST_METHOD'], rawurldecode($uri));
        switch ($routeInfo[0]) {
            case Dispatcher::NOT_FOUND:
                header('Content-Type: text/plain', true, 404);
                return "404 NOT FOUND";
            case Dispatcher::METHOD_NOT_ALLOWED:
                header('Content-Type: text/plain', true, 405);
                return "405 METHOD NOT ALLOWED";
            case Dispatcher::FOUND:
                $handler = $routeInfo[1];
                if (!is_array($handler)) {
                    return $handler();
                }
                $builder = new DependencyBuilder($_ENV, false, ...$this->dependencies);
                try {
                    return $builder->call($handler[0], $handler[1], $routeInfo[2]);
                } catch (Throwable $e) {
                    header('Content-Type: text/plain', true, 500);
                    return "500 SERVER ERROR";
                }
            default:
                header('Content-Type: text/plain', true, 500);
                return "500 SERVER ERROR";
        }
    }
}
