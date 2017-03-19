<?php

namespace Gephart\Framework\Line;

use Gephart\EventManager\Event;
use Gephart\EventManager\EventManager;
use Gephart\Framework\Template\Engine;
use Gephart\Routing\Router;

class ResponseListener
{
    /**
     * @var EventManager
     */
    private $event_manager;

    /**
     * @var Router
     */
    private $router;

    /**
     * @var Engine
     */
    private $engine;

    /**
     * @var float
     */
    private $microtime;

    public function __construct(
        EventManager $event_manager,
        Router $router,
        Engine $engine
    )
    {
        $this->event_manager = $event_manager;
        $this->router = $router;
        $this->engine = $engine;
        $this->microtime = microtime(true);

        $event_manager->attach(Router::RESPONSE_RENDER_EVENT, [$this, "responseRender"]);
    }

    public function responseRender(Event $event)
    {
        $line = $this->getLine();

        $response = $event->getParam("response");
        $response = str_replace("</body>", "$line</body>", $response);
        $event->setParams([
            "response" => $response
        ]);
    }

    private function getLine()
    {
        $router = $this->router;
        $routes = $router->getRoutes();
        $actual_route = $router->getActualRoute();
        $listeners = $this->event_manager->getListeners();
        $microtime = $this->microtime;

        return $this->engine->render("_framework/line.php", [
            "actual_route" => $actual_route,
            "routes" => $routes,
            "listeners" => $listeners,
            "microtime" => $microtime
        ]);
    }
}