<?php

namespace Gephart\Framework\Facade;

use Gephart\DependencyInjection\Container;

class Facade
{

    /**
     * @var Container
     */
    protected static $container;

    static function getAccessor()
    {
        throw new \RuntimeException("Facade must be implmented with method 'getAccessor'");
    }

    static function setDIContainer(Container $container)
    {
        static::$container = $container;
    }

    static function __callStatic($name, $arguments)
    {
        $accessor = static::getAccessor();
        $instance = self::$container->get($accessor);
        return $instance->$name(...$arguments);
    }
}