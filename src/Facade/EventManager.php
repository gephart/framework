<?php

namespace Gephart\Framework\Facade;

class EventManager extends Facade
{
    public static function getAccessor()
    {
        return \Gephart\EventManager\EventManagera::class;
    }
}
