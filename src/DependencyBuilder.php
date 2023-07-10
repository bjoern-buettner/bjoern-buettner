<?php

declare(strict_types=1);

namespace Me\BjoernBuettner;

use ReflectionClass;
use ReflectionException;
use ReflectionMethod;
use RuntimeException;

class DependencyBuilder
{
    /**
     * @var array<string, object>
     */
    private array $cache = [];
    public function __construct(
        private readonly array $params = [],
        private readonly array $interfaces = [],
    ) {
    }
    /**
     * @throws ReflectionException
     */
    private function build(string $class): object
    {
        if (isset($this->interfaces[$class])) {
            return $this->build($this->interfaces[$class]);
        }
        if (isset($this->cache[$class])) {
            return $this->cache[$class];
        }
        $rc = new ReflectionClass($class);
        $constructor = $rc->getConstructor();
        if (!$constructor) {
            return $this->cache[$class] = $rc->newInstance();
        }
        $params = $constructor->getParameters();
        $args = [];
        foreach ($params as $param) {
            $type = $param->getType();
            if (!$type) {
                throw new RuntimeException("Cannot resolve parameter {$param->getName()} of $class.");
            }
            if (!$type->isBuiltin()) {
                $args[] = $this->build($type->getName());
                continue;
            }
            if (isset($this->params[$class . '.' . $param->getName()])) {
                $args[] = $this->params[$class . '.' . $param->getName()];
                continue;
            }
            if ($type->allowsNull()) {
                $args[] = null;
                continue;
            }
            if ($param->isDefaultValueAvailable()) {
                $args[] = $param->getDefaultValue();
                continue;
            }
            if ($param->isOptional()) {
                break;
            }
            throw new RuntimeException("Cannot resolve parameter {$param->getName()} of $class.");
        }
        return $this->cache[$class] = $rc->newInstanceArgs($args);
    }

    /**
     * @throws ReflectionException
     */
    public function call(string $class, string $method, array $variables): string
    {
        $object = $this->build($class);
        $rm = new ReflectionMethod($object, $method);
        $params = $rm->getParameters();
        $args = [];
        foreach ($params as $param) {
            if (isset($variables[$param->getName()])) {
                $args[] = $variables[$param->getName()];
                continue;
            }
            if ($param->getType() && $param->getType()->isBuiltin()) {
                $args[] = $this->build($param->getType()->getName());
                continue;
            }
            if ($param->isDefaultValueAvailable()) {
                $args[] = $param->getDefaultValue();
                continue;
            }
            if ($param->isOptional()) {
                break;
            }
            throw new RuntimeException("Cannot resolve parameter {$param->getName()} of $method.");
        }
        return $rm->invokeArgs($object, $args);
    }
}
