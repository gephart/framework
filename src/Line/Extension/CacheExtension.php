<?php

namespace Gephart\Framework\Line\Extension;

use Gephart\EventManager\EventManager;
use Gephart\Framework\Configuration\FrameworkConfiguration;
use Gephart\Request\Request;
use Gephart\Routing\Router;
use Psr\Http\Message\ServerRequestInterface;

class CacheExtension implements ExtensionInterface
{
    /**
     * @var Request
     */
    private $request;

    /**
     * @var FrameworkConfiguration
     */
    private $framework_configuration;

    public function __construct(
        ServerRequestInterface $request,
        FrameworkConfiguration $framework_configuration,
        EventManager $event_manager
    )
    {
        $this->request = $request;
        $this->framework_configuration = $framework_configuration;

        $event_manager->attach(Router::START_RUN_EVENT, [$this, "clearAction"]);
    }

    public function getAlign()
    {
        return "right";
    }

    public function getTitle()
    {
        return "
            Clear cache
            <strong><span id=\"_gf-line__cache-clear\" style=\"display:none\">(Clearing...)</span></strong>
        " . $this->getScripts();
    }

    public function getContent()
    {
        return false;
    }

    public function getAttrs()
    {
        return "onclick=\"_gf_clearCache()\" style=\"cursor: pointer\"";
    }

    private function getScripts()
    {
        return <<<END
<script>
    function _gf_clearCache() {
        document.getElementById("_gf-line__cache-clear").style.display = "inline";
        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                if (this.responseText == "OK") {
                    document.getElementById("_gf-line__cache-clear").innerText = "(OK)";
                } else {
                    document.getElementById("_gf-line__cache-clear").innerText = "(Problem!)";
                }
                setTimeout(function () {
                    document.getElementById("_gf-line__cache-clear").style.display = "none";
                }, 3000);
            }
        };
        xhttp.open("GET", "?_line-action=clear-cache", true);
        xhttp.send();
    }
</script>
END;

    }

    public function getIcon()
    {
        return "data:image/svg+xml;base64,".base64_encode(trim("
             <?xml version=\"1.0\" encoding=\"iso-8859-1\"?>
            <!-- Generator: Adobe Illustrator 16.0.0, SVG Export Plug-In . SVG Version: 6.00 Build 0)  -->
            <!DOCTYPE svg PUBLIC \"-//W3C//DTD SVG 1.1//EN\" \"http://www.w3.org/Graphics/SVG/1.1/DTD/svg11.dtd\">
            <svg xmlns=\"http://www.w3.org/2000/svg\" xmlns:xlink=\"http://www.w3.org/1999/xlink\" version=\"1.1\" id=\"Capa_1\" x=\"0px\" y=\"0px\" width=\"512px\" height=\"512px\" viewBox=\"0 0 994 994\" style=\"enable-background:new 0 0 994 994;\" xml:space=\"preserve\">
            <g>
                <path d=\"M113.05,575.2L39.85,655.1c10.5-3.699,21.8-5.8,33.5-5.8h847.3c11.801,0,23,2,33.5,5.8l-73.1-79.899H113.05z\" fill=\"#FFFFFF\"/>
                <path d=\"M73.35,994h847.3c22.101,0,40-17.9,40-40V749.3c0-22.1-17.899-40-40-40H73.35c-22.1,0-40,17.9-40,40V954   C33.35,976.1,51.35,994,73.35,994z M809.05,791.6c33.1,0,60,26.9,60,60c0,33.101-26.9,60-60,60s-60-26.899-60-60   C749.05,818.5,775.951,791.6,809.05,791.6z\" fill=\"#FFFFFF\"/>
                <path d=\"M920.75,170.5c12.3,0,24,2.2,34.899,6.3L811.451,19.5C800.15,7.1,784.05,0,767.25,0h-270.2h-270.2   c-16.8,0-32.9,7.1-44.2,19.5L38.45,176.8c10.9-4.1,22.6-6.3,34.9-6.3H920.75z\" fill=\"#FFFFFF\"/>
                <path d=\"M73.35,515.2h94.7h658h94.6c22.101,0,40-17.9,40-40V270.5c0-22.1-17.899-40-40-40H73.35c-22.1,0-40,17.9-40,40v204.7   C33.35,497.3,51.35,515.2,73.35,515.2z M809.05,312.8c33.1,0,60,26.9,60,60c0,33.101-26.9,60-60,60s-60-26.899-60-60   C749.05,339.7,775.951,312.8,809.05,312.8z\" fill=\"#FFFFFF\"/>
            </g>
            <g>
            </g>
            <g>
            </g>
            <g>
            </g>
            <g>
            </g>
            <g>
            </g>
            <g>
            </g>
            <g>
            </g>
            <g>
            </g>
            <g>
            </g>
            <g>
            </g>
            <g>
            </g>
            <g>
            </g>
            <g>
            </g>
            <g>
            </g>
            <g>
            </g>
            </svg>
        "));
    }

    public function getPriority()
    {
        return 10;
    }

    public function clearAction()
    {
        $params = $this->request->getQueryParams();
        if (empty($params["_line-action"]) || (
                !empty($params["_line-action"]) && $params["_line-action"] != "clear-cache"
            )) {
            return;
        }

        $template_configuration = $this->framework_configuration->get("template");

        if (isset($template_configuration["twig"]) && !empty($template_configuration["twig"]["cache"])) {
            $dir = $this->framework_configuration->getDirectory() . "/../" . $template_configuration["twig"]["cache"];

            try {
                $this->deleteDir($dir, false);
                echo "OK";
            } catch (\Exception $exception) {
                echo $exception->getMessage();
            }

            @unlink($dir."/../classes_quality.ini");
        }

        exit;
    }

    private function deleteDir($dir, $remove_self = true) {
        $files = array_diff(scandir($dir), ['.','..']);

        foreach ($files as $file) {
            if (is_dir("$dir/$file")) {
                $this->deleteDir("$dir/$file");
            } else {
                @unlink("$dir/$file");
            }
        }

        if ($remove_self) {
            @chmod("$dir", 0777);
            @rmdir($dir);
        }
    }
}