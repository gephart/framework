<?php

namespace Gephart\Framework\Debugging;

use Gephart\EventManager\Event;
use Gephart\EventManager\EventManager;
use Gephart\Framework\Response\TemplateResponse;
use Gephart\Framework\Response\TemplateResponseFactory;
use Gephart\Routing\Router;

class Debugger
{
    /**
     * @var TemplateResponseFactory
     */
    private $response;

    /**
     * @var EventManager
     */
    private $event_manager;

    public function __construct(TemplateResponseFactory $response, EventManager $event_manager)
    {
        $this->response = $response;
        $this->event_manager = $event_manager;

        error_reporting(E_ALL);
        ini_set("display_error", 1);

        set_error_handler([$this, "errorHandler"]);
        set_exception_handler([$this, "exceptionHandler"]);
    }

    public function exceptionHandler($exception)
    {
        $type = get_class($exception);
        $errstr = $exception->getMessage();
        $errfile = $exception->getFile();
        $errline = $exception->getLine();

        $file = file_get_contents($exception->getFile());

        $traces = explode("\n", $exception->getTraceAsString());

        $response = $this->response->createResponse("_framework/error/exception.html.twig", [
            "file" => $file,
            "type" => $type,
            "errstr" => $errstr,
            "errfile" => $errfile,
            "errline" => $errline,
            "traces" => $traces
        ], 500);

        $stream = $response->getBody();
        $stream->rewind();
        $body = $stream->getContents();

        $body = $this->triggerReponseEvent($body);

        echo $body;
        exit;
    }

    public function errorHandler($errno, $errstr, $errfile, $errline)
    {
        switch ($errno) {
            case E_USER_ERROR:
            case E_ERROR:
                $type = "ERROR";
                break;

            case E_USER_WARNING:
            case E_WARNING:
                $type = "WARNING";
                break;

            case E_USER_NOTICE:
            case E_NOTICE:
                $type = "NOTICE";
                break;

            default:
                $type = "UNKNOWN";
        }

        $file = file_get_contents($errfile);

        $response = $this->response->createResponse("_framework/error/error.html.twig", [
            "file" => $file,
            "type" => $type,
            "errstr" => $errstr,
            "errfile" => $errfile,
            "errline" => $errline
        ]);

        $stream = $response->getBody();
        $stream->rewind();
        $body = $stream->getContents();

        $body = $this->triggerReponseEvent($body);


        $body = $this->triggerReponseEvent($body);

        echo $body;
        exit;
    }

    private function triggerReponseEvent($response)
    {
        $event = new Event();
        $event->setName(TemplateResponseFactory::RESPONSE_RENDER_EVENT);
        $event->setParams([
            "response" => $response
        ]);
        $this->event_manager->trigger($event);
        return $event->getParam("response");
    }
}
