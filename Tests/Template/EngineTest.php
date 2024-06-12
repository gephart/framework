<?php

include_once __DIR__ . "/../../vendor/autoload.php";

class EngineTest extends \PHPUnit\Framework\TestCase
{
    private $container;

    public function setUp(): void
    {
        $container = new \Gephart\DependencyInjection\Container();
        $configuration = $container->get(\Gephart\Configuration\Configuration::class);
        $configuration->setDirectory(__DIR__ . "/../engine-files/config/");

        $container->register((new \Gephart\Http\RequestFactory())->createFromGlobals(), \Psr\Http\Message\ServerRequestInterface::class);

        $this->container = $container;
    }

    public function testCleanPHP()
    {
        /** @var \Gephart\Framework\Template\Engine $engine */
        $engine = $this->container->get(\Gephart\Framework\Template\Engine::class);
        $render = $engine->render("clean.php", ["test" => "10"]);

        $this->assertTrue($render == "10 == 10");
    }

    public function testTwig()
    {
        /** @var \Gephart\Framework\Template\Engine $engine */
        $engine = $this->container->get(\Gephart\Framework\Template\Engine::class);
        $render = $engine->render("twig.html.twig", ["test" => "20"]);

        $this->assertTrue($render == "20 == 20");
    }
}