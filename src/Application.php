<?php

declare(strict_types=1);

namespace Me\BjoernBuettner;

use FastRoute\Dispatcher;
use FastRoute\RouteCollector;
use Me\BjoernBuettner\Pages\Login;
use Me\BjoernBuettner\Session\Factory;
use ReflectionException;
use Teto\HTTP\AcceptLanguage;
use Twig\Loader\FilesystemLoader;

use function FastRoute\simpleDispatcher;

class Application
{
    private array $routes = [
        'GET' => [],
        'POST' => [],
    ];
    private array $interfaces = [];
    private array $params = [];

    public function interface(string $interface, string $class): self
    {
        $this->interfaces[$interface] = $class;
        return $this;
    }
    public function param(string $class, string $param, mixed $value): self
    {
        $this->params[$class . '.' . $param] = $value;
        return $this;
    }
    public function res(string $route, array|callable $func): self
    {
        $this->routes['GET'][$route] = $func;
        return $this;
    }
    public function get(string $route, array|callable $func): self
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
    public function post(string $route, array|callable $func): self
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
                $builder = new DependencyBuilder($this->params, $this->interfaces);
                Factory::start($handler[0] instanceof Login);
                try {
                    return $builder->call($handler[0], $handler[1], $routeInfo[2]);
                } catch (ReflectionException $e) {
                    header('Content-Type: text/plain', true, 500);
                    return "500 SERVER ERROR";
                }
            default:
                header('Content-Type: text/plain', true, 500);
                return "500 SERVER ERROR";
        }
    }
}
