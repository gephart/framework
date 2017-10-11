<?php

include_once __DIR__ . "/../../vendor/autoload.php";

class TemplateResponseTest extends \PHPUnit\Framework\TestCase
{
    /** @var \Gephart\Framework\Response\TemplateResponseFactory $response */
    private $response;

    public function setUp()
    {
        $container = new \Gephart\DependencyInjection\Container();
        $configuration = $container->get(\Gephart\Configuration\Configuration::class);
        $configuration->setDirectory(__DIR__ . "/../engine-files/config/");

        $container->register((new \Gephart\Http\RequestFactory())->createFromGlobals(), \Psr\Http\Message\ServerRequestInterface::class);

        $this->response = $container->get(\Gephart\Framework\Response\TemplateResponseFactory::class);
    }

    public function testCleanPHP()
    {
        $render = $this->response->createResponse("clean.php", ["test" => "10"])->getBody();
        $render->rewind();
        $render = $render->getContents();

        $this->assertTrue($render == "10 == 10");
    }

    public function testTwig()
    {
        $render = $this->response->createResponse("twig.html.twig", ["test" => "20"])->getBody();
        $render->rewind();
        $render = $render->getContents();

        $this->assertTrue($render == "20 == 20");
    }
}