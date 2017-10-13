<?php

namespace Gephart\Framework\Response;

use Gephart\EventManager\Event;
use Gephart\EventManager\EventManager;
use Gephart\Http\Response;
use Gephart\Http\Stream;

class JsonResponseFactory
{
    const RESPONSE_RENDER_EVENT = __CLASS__ . "::RESPONSE_RENDER_EVENT";

    /**
     * @var EventManager
     */
    private $eventManager;

    public function __construct(EventManager $eventManager)
    {
        $this->eventManager = $eventManager;
    }

    public function createResponse($content, int $statusCode = 200, $headers = [])
    {
        $body = json_decode($content);
        $body = $this->triggerResponseRender($body);

        $stream = new Stream("php://temp", "rw");
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
}
