<?php

use Gephart\DependencyInjection\Container;
use Gephart\Configuration\Configuration;
use Gephart\Routing\Router;

include_once __DIR__ . "/../vendor/autoload.php";

$container = new Container();

$configuration = $container->get(Configuration::class);
$configuration->setDirectory(__DIR__ . "/../config");

if(in_array($_SERVER['REMOTE_ADDR'], [
    '127.0.0.1',
    '::1'
])){
    $container->get(\Gephart\Framework\Debugging\Debugger::class);
    $container->get(\Gephart\Framework\Line\EventListener\ResponseListener::class);
}

$router = $container->get(Router::class);
$router->run();