<?php

namespace Gephart\Framework\Facade;

class EntityManager extends Facade
{
    public static function getAccessor()
    {
        return \Gephart\ORM\EntityManager::class;
    }
}
