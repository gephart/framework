<?php

include_once __DIR__ . "/../../vendor/autoload.php";

class ResponseListenerTest extends \PHPUnit\Framework\TestCase
{
    private $container;

    public function setUp(): void
    {
        $this->setSuperglobals();

        $container = new \Gephart\DependencyInjection\Container();
        $configuration = $container->get(\Gephart\Configuration\Configuration::class);
        $configuration->setDirectory(__DIR__ . "/../config/");
        $container->register((new \Gephart\Http\RequestFactory())->createFromGlobals(), \Psr\Http\Message\ServerRequestInterface::class);
        $container->get(\Gephart\Framework\Line\EventListener\ResponseListener::class);

        /** @var \Gephart\Quality\Checker $quality_checker */
        $quality_checker = $container->get(\Gephart\Quality\Checker::class);
        $quality_checker->setDir(__DIR__ . "/../../src");

        $this->container = $container;
    }

    public function testResponseRender()
    {
        /** @var \Gephart\EventManager\EventManager $event_manager */
        $event_manager = $this->container->get(\Gephart\EventManager\EventManager::class);

        $event = new \Gephart\EventManager\Event();
        $event->setName(\Gephart\Framework\Response\TemplateResponseFactory::RESPONSE_RENDER_EVENT);
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
        $event->setName(\Gephart\Framework\Response\TemplateResponseFactory::RESPONSE_RENDER_EVENT);
        $event->setParams([
            "response" => null
        ]);

        $event_manager->trigger($event);

        $response = $event->getParam("response");

        $this->assertTrue($response == null);
    }

    public function setSuperglobals()
    {
        $_GET = ["test" => "get"];
        $_POST = ["test" => "post"];
        $_COOKIE = ["test" => "cookie"];
        $_SERVER['SERVER_PROTOCOL'] = "HTTP/1.0";
        $_SERVER['SERVER_PORT'] = "80";
        $_SERVER['SERVER_NAME'] = "www.gephart.cz";
        $_SERVER['REQUEST_URI'] = "/index.html";
        $_SERVER['REQUEST_METHOD'] = "GET";
    }
}