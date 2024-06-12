<?php

include_once __DIR__ . "/../../vendor/autoload.php";

class FrameworkConfigurationTest extends \PHPUnit\Framework\TestCase
{
    private $container;

    public function setUp(): void
    {
        $container = new \Gephart\DependencyInjection\Container();
        $configuration = $container->get(\Gephart\Configuration\Configuration::class);
        $configuration->setDirectory(__DIR__ . "/../config/");

        $this->container = $container;
    }

    public function testParams()
    {
        /** @var \Gephart\Framework\Configuration\FrameworkConfiguration $framework_configuration */
        $framework_configuration = $this->container->get(\Gephart\Framework\Configuration\FrameworkConfiguration::class);

        $this->assertTrue(is_array($framework_configuration->get("template")));
        $this->assertTrue(is_array($framework_configuration->get("template")["twig"]));
        $this->assertTrue($framework_configuration->get("template")["dir"] == "../skeleton/template/");
    }
}