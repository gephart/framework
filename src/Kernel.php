<?php

namespace Gephart\Framework;

use Gephart\Configuration\Configuration;
use Gephart\DependencyInjection\Container;
use Gephart\EventManager\EventManager;
use Gephart\Framework\Debugging\Debugger;
use Gephart\Framework\EventListener\SecurityListener;
use Gephart\Framework\Line\EventListener\ResponseListener;
use Gephart\Routing\Router;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;

class Kernel
{
    const DEV_ENVIRONMENT = "dev";
    const PROD_ENVIRONMENT = "prod";

    const RUN_EVENT = __CLASS__ . "::RUN_EVENT";

    /**
     * @var Container
     */
    protected $container;

    /**
     * @var Configuration
     */
    protected $configuration;

    /**
     * @var string
     */
    protected $environment;

    /**
     * @var EventManager
     */
    protected $eventManager;

    public function __construct(ServerRequestInterface $request)
    {
        $this->container = new Container();
        $this->configuration = $this->container->get(Configuration::class);
        $this->eventManager = $this->container->get(EventManager::class);

        $this->autodetectEnvironment();

        $config_dir = realpath(__DIR__ . "/../../../../config");
        if (is_dir($config_dir)) {
            $this->setConfiguration($config_dir);
        }

        $this->registerRequest($request);
    }

    public function setConfiguration(string $dir)
    {
        $this->configuration->setDirectory($dir);
    }

    public function setEnvironment(string $environment)
    {
        $this->environment = $environment;
    }

    public function registerRequest(ServerRequestInterface $request)
    {
        $this->container->register($request, ServerRequestInterface::class);
    }

    public function registerServices(array $services, string $environment = "")
    {
        if (empty($environment) || $environment == $this->environment) {
            foreach ($services as $service) {
                $this->container->get($service);
            }
        }
    }

    public function run(): ResponseInterface
    {
        $this->eventManager->trigger(self::RUN_EVENT, $this);

        $this->registerServices([
            Debugger::class,
            ResponseListener::class
        ], self::DEV_ENVIRONMENT);

        $this->registerServices([
            SecurityListener::class
        ]);

        $router = $this->container->get(Router::class);
        return $router->run();
    }

    protected function autodetectEnvironment()
    {
        if (in_array(@$_SERVER['REMOTE_ADDR'], [
            '127.0.0.1',
            '::1'
        ])) {
            $this->setEnvironment(Kernel::DEV_ENVIRONMENT);
        } else {
            $this->setEnvironment(Kernel::PROD_ENVIRONMENT);
        }
    }

    public function render(ResponseInterface $response): string
    {
        http_response_code($response->getStatusCode());

        $headers = $response->getHeaders();
        foreach ($headers as $headerName => $headerParams) {
            header($headerName . ": " . implode(",", $headersParams));
        }

        $stream = $response->getBody();
        $stream->rewind();
        $body = $stream->getContents();

        return $body;
    }
}
