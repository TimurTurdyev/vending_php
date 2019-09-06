<?php
declare(strict_types=1);

namespace Framework;

use LogicException;
use ReflectionClass;

final class Container
{
    /** @var callable[] */
    private $factories = [];

    /** @var array */
    private $instances = [];

    public function set(string $id, callable $factory): void
    {
        $this->factories[$id] = $factory;
        unset($this->instances[$id]);
    }

    public function get(string $id)
    {
        if (!array_key_exists($id, $this->instances)) {
            $this->instances[$id] = isset($this->factories[$id])
                ? ($this->factories[$id])($this)
                : $this->build($id);
        }

        return $this->instances[$id];
    }

    // класс без фабрики собирается по типам параметров конструктора
    private function build(string $class)
    {
        if (!class_exists($class)) {
            throw new LogicException(sprintf('Service "%s" is not defined', $class));
        }

        $reflection = new ReflectionClass($class);
        $constructor = $reflection->getConstructor();

        if ($constructor === null) {
            return new $class();
        }

        $arguments = [];

        foreach ($constructor->getParameters() as $parameter) {
            $type = $parameter->getType();

            if ($type !== null && !$type->isBuiltin()) {
                $arguments[] = $this->get($type->getName());
            } elseif ($parameter->isDefaultValueAvailable()) {
                $arguments[] = $parameter->getDefaultValue();
            } else {
                throw new LogicException(sprintf('Cannot resolve $%s for "%s"', $parameter->getName(), $class));
            }
        }

        return $reflection->newInstanceArgs($arguments);
    }
}
