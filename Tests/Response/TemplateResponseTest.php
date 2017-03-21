<?php

include_once __DIR__ . "/../../vendor/autoload.php";

class TemplateResponseTest extends \PHPUnit\Framework\TestCase
{
    private $response;

    public function setUp()
    {
        $container = new \Gephart\DependencyInjection\Container();
        $configuration = $container->get(\Gephart\Configuration\Configuration::class);
        $configuration->setDirectory(__DIR__ . "/../engine-files/config/");
        $container->get(\Gephart\Framework\Line\ResponseListener::class);

        $this->response = $container->get(\Gephart\Framework\Response\TemplateResponse::class);
    }

    public function testCleanPHP()
    {
        $render = $this->response->template("clean.php", ["test" => "10"])->render();

        $this->assertTrue($render == "10 == 10");
    }

    public function testTwig()
    {
        $render = $this->response->template("twig.html.twig", ["test" => "20"])->render();

        $this->assertTrue($render == "20 == 20");
    }
}