<?php

namespace Gephart\Framework\Facade;

use Psr\Http\Message\ServerRequestInterface;

class Request extends Facade
{
    public static function getAccessor()
    {
        return ServerRequestInterface::class;
    }
}
