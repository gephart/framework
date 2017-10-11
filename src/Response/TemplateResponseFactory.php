<?php

namespace Gephart\Framework\Response;

use Gephart\EventManager\Event;
use Gephart\EventManager\EventManager;
use Gephart\Framework\Template\Engine;
use Gephart\Http\Response;
use Gephart\Http\Stream;

class TemplateResponseFactory
{
    const RESPONSE_RENDER_EVENT = __CLASS__ . "::RESPONSE_RENDER_EVENT";
    const DATA_TRANSMIT_EVENT = __CLASS__ . "::DATA_TRANSMIT_EVENT";

    /**
     * @var Engine
     */
    private $engine;

    /**
     * @var EventManager
     */
    private $eventManager;

    public function __construct(Engine $engine, EventManager $eventManager)
    {
        $this->engine = $engine;
        $this->eventManager = $eventManager;
    }

    public function createResponse(string $template, array $data = [], int $statusCode = 200, $headers = [])
    {
        $data = $this->triggerDataTransmit($data);
        $body = $this->engine->render($template, $data);
        $body = $this->triggerResponseRender($body);

        $stream = new Stream("php://temp","rw");
        $stream->write($body);

        $response = new Response($stream, $statusCode, $headers);
        return $response;
    }

    private function triggerResponseRender($body): string
    {
        $event = new Event();
        $event->setName(self::RESPONSE_RENDER_EVENT);
        $event->setParams([
            "response" => $body
        ]);

        $this->eventManager->trigger($event);

        return $event->getParam("response");
    }

    private function triggerDataTransmit(array $data = []): array
    {
        $event = new Event();
        $event->setName(self::DATA_TRANSMIT_EVENT);
        $event->setParams([
            "data" => $data
        ]);

        $this->eventManager->trigger($event);

        return $event->getParam("data");
    }

}