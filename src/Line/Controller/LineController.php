<?php

namespace Gephart\Framework\Line\Controller;

use Gephart\DependencyInjection\Container;
use Gephart\EventManager\EventManager;
use Gephart\Framework\Line\Action\ClearCacheAction;
use Gephart\Framework\Line\Extension\ActualRouteExtension;
use Gephart\Framework\Line\Extension\CacheExtension;
use Gephart\Framework\Line\Extension\ExtensionInterface;
use Gephart\Framework\Line\Extension\HeaderExtension;
use Gephart\Framework\Line\Extension\ListenersExtension;
use Gephart\Framework\Line\Extension\ListenersExtenstion;
use Gephart\Framework\Line\Extension\QualityExtension;
use Gephart\Framework\Line\Extension\RoutesExtension;
use Gephart\Framework\Line\Extension\SecurityExtension;
use Gephart\Framework\Line\Extension\TimerExtension;
use Gephart\Framework\Line\Extension\TimerExtenstion;
use Gephart\Framework\Template\Engine;
use Gephart\Routing\Router;

class LineController
{
    /**
     * @var Engine
     */
    private $engine;

    /**
     * @var Container
     */
    private $container;

    /**
     * @var array
     */
    private $extensions;

    public function __construct(
        Container $container,
        Engine $engine
    ) {
        $this->container = $container;
        $this->engine = $engine;
        $this->extensions = [];

        $this->registerExtension(RoutesExtension::class);
        $this->registerExtension(ActualRouteExtension::class);
        $this->registerExtension(HeaderExtension::class);
        $this->registerExtension(CacheExtension::class);
        $this->registerExtension(ListenersExtension::class);
        $this->registerExtension(TimerExtension::class);
        $this->registerExtension(SecurityExtension::class);
        //$this->registerExtension(QualityExtension::class);
    }

    public function registerExtension(string $extension_classname)
    {
        $extension = $this->container->get($extension_classname);

        if ($extension instanceof ExtensionInterface) {
            $this->extensions[] = $extension;
        }
    }

    public function unregisterExtension(string $extension_classname)
    {
        foreach ($this->extensions as $key => $extension) {
            if ($extension instanceof $extension_classname) {
                unset($this->extensions[$key]);
            }
        }
    }

    public function getLine()
    {
        $extensions = $this->extensions;
        usort($extensions, function (ExtensionInterface $a, ExtensionInterface $b) {
            return $a->getPriority() <=> $b->getPriority();
        });

        $base64_encode = function ($string) {
            return base64_encode($string);
        };
        $file_get_contents = function ($string) {
            return file_get_contents($string);
        };

        return $this->engine->render("_framework/line.html.twig", [
            "extensions" => $extensions,
            "base64_encode" => $base64_encode,
            "file_get_contents" => $file_get_contents
        ]);
    }
}
