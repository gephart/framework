<?php

namespace Gephart\Framework\EventListener;

use Gephart\EventManager\Event;
use Gephart\EventManager\EventManager;
use Gephart\Framework\Line\Controller\LineController;
use Gephart\Routing\Router;
use Gephart\Security\Authenticator\Authenticator;
use Gephart\Security\SecurityReader;

class SecurityListener
{

    /**
     * @var Authenticator
     */
    private $authenticator;

    /**
     * @var SecurityReader
     */
    private $security_reader;

    public function __construct(
        EventManager $event_manager,
        Authenticator $authenticator,
        SecurityReader $security_reader
    )
    {
        $this->authenticator = $authenticator;
        $this->security_reader = $security_reader;

        $event_manager->attach(Router::BEFORE_CALL_EVENT, [$this, "beforeCall"]);
    }

    public function beforeCall(Event $event)
    {
        $controller = $event->getParam("controller");
        $action = $event->getParam("action");

        $must_have_role = $this->security_reader->getMustHaveRole($controller, $action);

        if ($must_have_role && !$this->authenticator->isGranted($must_have_role)) {
            @header('HTTP/1.0 403 Forbidden');
            throw new \Exception("403 Forbidden");
        }
    }
}