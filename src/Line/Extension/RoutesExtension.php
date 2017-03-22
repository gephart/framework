<?php

namespace Gephart\Framework\Line\Extension;

use Gephart\Routing\Router;

class RoutesExtension implements ExtensionInterface
{
    /**
     * @var Router
     */
    private $router;

    public function __construct(Router $router)
    {
        $this->router = $router;
    }

    public function getAlign()
    {
        return "right";
    }

    public function getTitle()
    {
        return "Routes";
    }

    public function getContent()
    {
        $routes = $this->router->getRoutes();

        $content = "
            <table>
                <tr>
                    <th>Rule</th>
                    <th>Action</th>
                    <th>Name</th>
                </tr>
        ";

        foreach ($routes as $route):
            $content .= "
                <tr>
                    <td>".$route->getRule()."</td>
                    <td>".$route->getController()."::".$route->getAction()."</td>
                    <td>".$route->getName()."</td>
                </tr>
            ";
        endforeach;
        $content .= "</table>";

        return $content;
    }

    public function getIcon()
    {
        return "data:image/svg+xml;base64,".base64_encode(trim("
            <?xml version=\"1.0\" encoding=\"utf-8\"?>
            <!DOCTYPE svg PUBLIC \"-//W3C//DTD SVG 1.1//EN\" \"http://www.w3.org/Graphics/SVG/1.1/DTD/svg11.dtd\">
            <svg xmlns=\"http://www.w3.org/2000/svg\" xmlns:xlink=\"http://www.w3.org/1999/xlink\" version=\"1.1\" width=\"512px\" height=\"512px\" viewBox=\"0 0 16 16\">
            <path fill=\"#FFFFFF\" d=\"M16 4h-16v3h3.2l3.8 3.6c1.6 1.5 3.6 2.4 5.8 2.4h3.2v-3h-3.2c-1.4 0-2.7-0.5-3.7-1.5l-1.6-1.5h8.5v-3z\"/>
            </svg>
        "));
    }

    public function getPriority()
    {
        return 100;
    }
}