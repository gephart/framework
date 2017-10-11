<?php

namespace Gephart\Framework\Line\EventListener;

use Gephart\EventManager\Event;
use Gephart\EventManager\EventManager;
use Gephart\Framework\Line\Controller\LineController;
use Gephart\Framework\Response\TemplateResponseFactory;

class ResponseListener
{
    /**
     * @var LineController
     */
    private $line_controller;

    public function __construct(EventManager $event_manager, LineController $line_controller)
    {
        $this->line_controller = $line_controller;

        $event_manager->attach(TemplateResponseFactory::RESPONSE_RENDER_EVENT, [$this, "responseRender"]);
    }

    public function responseRender(Event $event)
    {
        $line = $this->line_controller->getLine();

        $response = $event->getParam("response");
        $response = str_replace("</body>", "$line</body>", $response);
        $event->setParams([
            "response" => $response
        ]);
    }
}