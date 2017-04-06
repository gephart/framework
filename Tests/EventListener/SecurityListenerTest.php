<?php

include_once __DIR__ . "/../../vendor/autoload.php";


class TestResponse implements \Gephart\Response\ResponseInterface
{
    public function render()
    {
        return "";
    }
}

class TestController {
    /**
     * @var \Gephart\Security\Authenticator\Authenticator
     */
    private $authenticator;

    public function __construct(\Gephart\Security\Authenticator\Authenticator $authenticator)
    {
        $this->authenticator = $authenticator;
    }

    /**
     * @Route /test/security
     * @Security ROLE_USER
     */
    public function security(){
        return new TestResponse();
    }

    /**
     * @Route /test/login
     */
    public function login(){
        $this->authenticator->authorise("admin", "admin.123");
        return new TestResponse();
    }
}

class SecurityListenerTest extends \PHPUnit\Framework\TestCase
{

    public function testSecurityBad()
    {
        $kernel = new \Gephart\Framework\Kernel();
        $kernel->setConfiguration(__DIR__ . "/../config/");

        $test = false;
        try {
            $_GET["_route"] = "test/security";
            $kernel->run();
        } catch (Exception $exception) {
            $test = true;
        }

        $this->assertTrue($test);
    }

    public function testSecurityGood()
    {
        $kernel = new \Gephart\Framework\Kernel();
        $kernel->setConfiguration(__DIR__ . "/../config/");

        $test = false;
        try {
            $_GET["_route"] = "test/login";
            $kernel->run();

            $_GET["_route"] = "test/security";
            $kernel->run();
            $test = true;
        } catch (Exception $exception) {echo $exception->getMessage();}

        $this->assertTrue($test);
    }
}