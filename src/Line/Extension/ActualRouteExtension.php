<?php

namespace Gephart\Framework\Line\Extension;

use Gephart\Routing\Router;

class ActualRouteExtension implements ExtensionInterface
{
    /**
     * @var Router
     */
    private $router;

    public function __construct(Router $router)
    {
        $this->router = $router;
    }

    public function getAlign()
    {
        return "left";
    }

    public function getTitle()
    {
        $route = $this->router->getActualRoute();

        if ($route) {
            $title = $route->getController()."::".$route->getAction();
        } else {
            $title = "Unknown";
        }

        return $title;
    }

    public function getContent()
    {
        return false;
    }

    public function getIcon()
    {
        return false;
    }

    public function getPriority()
    {
        return 100;
    }
}