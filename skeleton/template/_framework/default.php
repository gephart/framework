<!doctype html>
<html>
<head>
    <title>Gephart - PHP framework</title>
    <link href="https://fonts.googleapis.com/css?family=Inconsolata:400,700|Raleway:100,100i,200,200i,300,300i,400,400i,500,500i,600,600i,700,700i,800,800i,900,900i"
          rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
        }

        body {
            font-family: "Raleway";
            color: #222;
        }

        code {
            font-family: "Inconsolata";
        }

        header {
            text-align: center;
            background: url('data:image/jpeg;base64,<?=base64_encode(file_get_contents(__DIR__."/assets/img/background.jpg"))?>') no-repeat center center / cover;
            background-attachment: fixed;
            color: #fff;
            padding: 100px 24px;
            position: relative;
        }

        header:before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, .5);
        }

        main {
            padding: 24px;
            max-width: 900px;
            margin: 0 auto;
        }

        aside {
            width: 200px;
            float: right;
        }

        .content {
            width: calc(100% - 200px);
        }

        p, h3, h4, pre, ul {
            margin-bottom: 24px;
            line-height: 1.5em;
            font-size: 16px;
        }

        code {
            padding: 12px !important;
            background: #eee !important;
            overflow: auto;
        }

        h1, h2 {
            position: relative;
        }

        h1 {
            font-size: 72px;
            font-weight: 900;
            margin-bottom: 24px;
        }

        h2 {
            font-weight: 100;
            font-size: 32px
        }

        h3 {
            font-size: 24px;
            border-bottom: 1px #222 solid;
        }

        ul {
            margin-left: 24px;
            list-style: none;
        }

        ul a {
            color: #4078f2;
            text-shadow: 1px 0 0 #fff, 0 1px 0 #fff, 1px 1px 0 #fff, -1px 0 0 #fff, 0 -1px 0 #fff, -1px 1px 0 #fff;
            text-decoration: none;
            background-image: linear-gradient(to right, currentColor 0%, currentColor 100%);
            background-repeat: repeat-x;
            background-position: left bottom 1px;
            background-size: 100% 1px;
        }

        ul ul {
            margin-left: 18px;
        }
    </style>
</head>
<body>
<header>
    <h1>Gephart</h1>
    <h2>PHP framework</h2>
</header>
<main>
    <aside>
        <ul>
            <li><a href="#project-structure">Project structure</a></li>
            <li><a href="#routing">Routing</a></li>
            <li><a href="#controllers">Controllers</a></li>
            <li><a href="#templates">Templates</a></li>
            <li><a href="#dependency-injection">Dependency injection</a></li>
            <li><a href="#event-manager">Event manager</a></li>
        </ul>
    </aside>
    <div class="content">
        <a id="project-structure"></a>
        <h3>Project structure</h3>
        <pre><code class="php">/config
/src
    /App
        /Controller
        ...
/template
/vendor
/web
    /assets
        /css
        /img
        /js
        ...
    .htaccess
    favicon.ico
    index.php
composer.json
</code></pre>

        <a id="routing"></a>
        <h3>Routing</h3>

        <h4>.htaccess</h4>
        <pre><code class="htaccess">RewriteEngine On
RewriteBase /
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule ^(.*)$ /index.php?_route=$1 [L,QSA]</code></pre>

        <h4>index.php</h4>
        <p>Main file
        <ul>
            <li>Create DI container
            <li>Set directory of configuration
            <li>Run router
        </ul>
        To je celé kouzlo.
        <pre><code class="php">&lt;?php

use Gephart\DependencyInjection\Container;
use Gephart\Configuration\Configuration;
use Gephart\Routing\Router;

include_once __DIR__ . "/vendor/autoload.php";

$container = new Container();

$configuration = $container->get(Configuration::class);
$configuration->setDirectory(__DIR__ . "/config");

$router = $container->get(Router::class);
$router->run();</code></pre>

        <h4>/config/routing.json</h4>
        <p>Autoloading routes from controllers

        <pre><code class="json">{
  "autoload": "src/"
}</code></pre>

        <h4>/src/App/Controller/DefaultController.php</h4>


        <pre><code class="php">&lt;?php

namespace App\Controller;

use Gephart\Response\Response;

class DefaultController
{
    /**
     * @Route /
     */
    public function index() {
        return new Response("Hello World");
    }
}</code></pre>

        <p>Settings of route:</p>
        <pre><code class="php">
/**
 * @Route {
 *  "rule": "/page/{slug}/{limit}/{offset}",
 *  "name": "page_detail",
 *  "requirements": {
 *      "limit": "[0-9]+",
 *      "offset": "[0-9]+"
 *  }
 * }
 */
public function index($slug, $limit, $offset) {
    return new Response("Hello " . $slug);
}
</code></pre>

        <h4>RoutePrefix</h4>

        <p>Next code catch request on "/admin/page".

        <pre><code class="php">&lt;?php

namespace App\Controller;

use Gephart\Response\Response;

/**
 * @RoutePrefix /admin
 */
class AdminController
{
    /**
     * @Route /page
     */
    public function index() {
        return new Response("Hello Admin");
    }
}</code></pre>

        <h4>Generating URL</h4>
        <pre><code class="php">&lt;?php

namespace App\Controller;

use Gephart\Response\Response;
use Gephart\Routing\Router;

class DefaultController
{

    /**
     * @var Router
     */
    private $router;

    public function __construct(Router $router)
    {
        $this->router = $router;
    }

    /**
     * @Route {
     *  "rule": "/page/{slug}/{limit}/{offset}",
     *  "name": "page_detail",
     *  "requirements": {
     *      "limit": "[0-9]+",
     *      "offset": "[0-9]+"
     *  }
     * }
     */
    public function index($slug, $limit, $offset) {
        $url = $this->router->generateUrl("page_detail", [
            "slug" => "articles",
            "limit" => "10",
            "offset" => "20",
        ]);

        return new Response("Hello World - " . $url);
    }
}</code></pre>


        <a id="controllers"></a>
        <h3>Controllers</h3>

        <p>Controllers in section Routing or Templates</p>

        <a id="templates"></a>
        <h3>Templates</h3>

        <h4>config/framework.json</h4>
        <pre><code class="json">{
  "template": {
    "dir": "template/",
    "twig": {
      "cache": "cache/twig/"
    }
  }
}</code></pre>

        <h4>cache/twig/</h4>

        <p>Directory must have permission for write.</p>

        <h4>src/App/Controller/DefaultController.php</h4>
        <pre><code class="php">&lt;?php

namespace App\Controller;

use Gephart\Framework\Response\TemplateResponse;

final class DefaultController
{
    /**
     * @var TemplateResponse
     */
    private $response;

    public function __construct(TemplateResponse $template_response)
    {
        $this->response = $template_response;
    }

    /**
     * @Route /
     * @return TemplateResponse
     */
    public function index() {
        return $this->response->template("_framework/default.php");

        // Or twig template
        // return $this->response->template("_framework/default.html.twig");
    }
}</code></pre>

        <a id="dependency-injection"></a>
        <h3>Dependency injection</h3>

        <p>Usign:
        <pre><code class="php">class A
{
    public function hello(string $world): string
    {
        return "hello " . $world;
    }
}

class B
{
    private $a;

    public function __construct(A $a)
    {
        $this-&gt;a = $a;
    }

    public function render()
    {
        return $this-&gt;a-&gt;hello("world");
    }
}

// Bad
$a = new A();
$b = new B($a);
$b-&gt;render();

// Good
$container = new Container();
$b = $container-&gt;get(B::class);
$b-&gt;render(); // hello world
</code></pre>

        <a id="event-manager"></a>
        <h3>Event manager</h3>


        <p>Rendered string from Response() has event Router::RESPONSE_RENDER_EVENT.
        <p>Registering listener in main file:

        <h4>index.php</h4>

        <pre><code class="php">&lt;?php

use Gephart\DependencyInjection\Container;
use Gephart\Configuration\Configuration;
use Gephart\Routing\Router;

include_once __DIR__ . "/vendor/autoload.php";

$container = new Container();

$configuration = $container->get(Configuration::class);
$configuration->setDirectory(__DIR__ . "/config");

<strong>$container->get(\App\EventListener\ResponseListener::class);</strong>

$router = $container->get(Router::class);
$router->run();</code></pre>

        <h4>src/App/EventListener/ResponseListener.php</h4>
        <pre><code class="php">&lt;?php

namespace App\EventListener;

use Gephart\EventManager\Event;
use Gephart\EventManager\EventManager;
use Gephart\Routing\Router;

class ResponseListener
{
    public function __construct(EventManager $event_manager)
    {
        $event_manager->attach(Router::RESPONSE_RENDER_EVENT, [$this, "reponseRender"]);
    }

    public function responseRender(Event $event)
    {
        $response = $event->getParam("response");
        $response .= "Hello by listener";

        $event->setParams([
            "response" => $response
        ]);
    }
}</code></pre>

    </div>
</main>
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/highlight.js/9.9.0/styles/atom-one-light.min.css">
<script src="//cdnjs.cloudflare.com/ajax/libs/highlight.js/9.9.0/highlight.min.js"></script>
<script>hljs.initHighlightingOnLoad();</script>
</body>
</html>