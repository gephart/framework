<?php

namespace Gephart\Framework\Facade;

class Router extends Facade
{
    public static function getAccessor()
    {
        return \Gephart\Routing\Router::class;
    }
}
