<?php

include_once __DIR__ . "/../../vendor/autoload.php";

class ResponseListenerTest extends \PHPUnit\Framework\TestCase
{
    private $container;

    public function setUp()
    {
        $container = new \Gephart\DependencyInjection\Container();
        $configuration = $container->get(\Gephart\Configuration\Configuration::class);
        $configuration->setDirectory(__DIR__ . "/../config/");
        $container->get(\Gephart\Framework\Line\ResponseListener::class);

        $this->container = $container;
    }

    public function testResponseRender()
    {
        /** @var \Gephart\EventManager\EventManager $event_manager */
        $event_manager = $this->container->get(\Gephart\EventManager\EventManager::class);

        $event = new \Gephart\EventManager\Event();
        $event->setName(\Gephart\Routing\Router::RESPONSE_RENDER_EVENT);
        $event->setParams([
            "response" => "<body></body>"
        ]);

        $event_manager->trigger($event);

        $response = $event->getParam("response");

        $this->assertTrue($response != "<body></body>");
        $this->assertTrue(strpos($response, "gf-line") > 1);
    }

    public function testNullResponseRender()
    {
        /** @var \Gephart\EventManager\EventManager $event_manager */
        $event_manager = $this->container->get(\Gephart\EventManager\EventManager::class);

        $event = new \Gephart\EventManager\Event();
        $event->setName(\Gephart\Routing\Router::RESPONSE_RENDER_EVENT);
        $event->setParams([
            "response" => null
        ]);

        $event_manager->trigger($event);

        $response = $event->getParam("response");

        $this->assertTrue($response == null);
    }
}