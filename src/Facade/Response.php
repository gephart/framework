<?php

namespace Gephart\Framework\Facade;

use Gephart\Framework\Response\ResponseFactory;

class Response extends Facade
{
    public static function getAccessor()
    {
        return ResponseFactory::class;
    }
}
