<?php

namespace Gephart\Framework\Facade;

use Gephart\DependencyInjection\Container;

class Facade
{

    /**
     * @var Container
     */
    protected static $container;

    public static function getAccessor()
    {
        throw new \RuntimeException("Facade must be implmented with method 'getAccessor'");
    }

    public static function setDIContainer(Container $container)
    {
        static::$container = $container;
    }

    public static function __callStatic($name, $arguments)
    {
        $accessor = static::getAccessor();
        $instance = self::$container->get($accessor);
        return $instance->$name(...$arguments);
    }
}
