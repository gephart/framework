<?php

namespace Gephart\Framework\Line;

use Gephart\Configuration\Configuration;
use Gephart\EventManager\Event;
use Gephart\EventManager\EventManager;
use Gephart\Framework\Configuration\FrameworkConfiguration;
use Gephart\Framework\Template\Engine;
use Gephart\Request\Request;
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
     * @var Configuration
     */
    private $configuration;

    /**
     * @var float
     */
    private $microtime;

    public function __construct(
        EventManager $event_manager,
        Router $router,
        Engine $engine,
        FrameworkConfiguration $configuration,
        Request $request
    )
    {
        $this->event_manager = $event_manager;
        $this->router = $router;
        $this->engine = $engine;
        $this->configuration = $configuration;
        $this->microtime = microtime(true);

        if ($request->get("_line-action") == "clear-cache") {
            $this->requestClearCache();
            exit;
        }

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

    private function requestClearCache()
    {
        $template_configuration = $this->configuration->get("template");
        if (isset($template_configuration["twig"]) && !empty($template_configuration["twig"]["cache"])) {
            $dir = $this->configuration->getDirectory() . "/../" . $template_configuration["twig"]["cache"];
            try {
                $this->deleteDir($dir, false);
                echo "OK";
            } catch (\Exception $exception) {
                echo $exception->getMessage();
            }
        }
    }

    private function deleteDir($dir, $remove_self = true) {
        $files = array_diff(scandir($dir), ['.','..']);

        foreach ($files as $file) {
            if (is_dir("$dir/$file")) {
                $this->deleteDir("$dir/$file");
            } else {
                @unlink("$dir/$file");
            }
        }

        if ($remove_self) {
            @chmod("$dir", 0777);
            @rmdir($dir);
        }
    }
}