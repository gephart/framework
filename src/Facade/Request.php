<?php

namespace Gephart\Framework\Facade;

use Psr\Http\Message\ServerRequestInterface;

class Response extends Facade
{
    public static function getAccessor()
    {
        return ServerRequestInterface::class;
    }
}
