<?php

namespace Gephart\Framework\Facade;

use Psr\Http\Message\ServerRequestInterface;

class Response extends Facade
{
    static function getAccessor()
    {
        return ServerRequestInterface::class;
    }
}