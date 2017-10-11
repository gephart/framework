<?php

include_once __DIR__ . "/../../vendor/autoload.php";


class TestResponse extends \Gephart\Http\Response
{
    public function __construct()
    {
        $stream = new \Gephart\Http\Stream("php://temp","rw");
        $stream->write("");

        parent::__construct($stream);
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

        $test = false;
        try {
            $this->setSuperglobals();
            $_GET["_route"] = "test/security";
            $kernel = new \Gephart\Framework\Kernel((new \Gephart\Http\RequestFactory())->createFromGlobals());
            $kernel->setConfiguration(__DIR__ . "/../config/");
            $kernel->run();
        } catch (Exception $exception) {
            $test = true;
        }

        $this->assertTrue($test);
    }

    public function testSecurityGood()
    {
        $test = false;

        try {
            $this->setSuperglobals();

            $_GET["_route"] = "test/login";
            $kernel = new \Gephart\Framework\Kernel((new \Gephart\Http\RequestFactory())->createFromGlobals());
            $kernel->setConfiguration(__DIR__ . "/../config/");
            $kernel->run();

            $_GET["_route"] = "test/security";
            $kernel->registerRequest((new \Gephart\Http\RequestFactory())->createFromGlobals());

            $kernel->run();

            $test = true;
        } catch (Exception $exception) {}

        $this->assertTrue($test);
    }

    public function setSuperglobals()
    {
        $_GET = ["test" => "get"];
        $_POST = ["test" => "post"];
        $_COOKIE = ["test" => "cookie"];
        $_SERVER['SERVER_PROTOCOL'] = "HTTP/1.0";
        $_SERVER['SERVER_PORT'] = "80";
        $_SERVER['SERVER_NAME'] = "www.gephart.cz";
        $_SERVER['REQUEST_URI'] = "/index.html";
        $_SERVER['REQUEST_METHOD'] = "GET";
    }
}