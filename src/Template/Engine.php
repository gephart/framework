<?php

namespace Gephart\Framework\Template;

use Gephart\Framework\Configuration\FrameworkConfiguration;
use Gephart\Routing\Router;

class Engine
{
    /**
     * @var FrameworkConfiguration
     */
    private $configuration;

    /**
     * @var Router
     */
    private $router;

    /**
     * @var \Twig_Environment
     */
    private $twig;

    public function __construct(FrameworkConfiguration $configuration, Router $router)
    {
        $this->configuration = $configuration;
        $this->router = $router;

        $template = $this->configuration->get("template");
        if (isset($template["twig"])) {
            $this->twig = $this->getTwig();
        }
    }

    public function render(string $template, array $data = []): string
    {
        $data = array_merge($this->getBasicVariables(), $data);

        if ($this->twig && substr($template, -5) == ".twig") {
            return $this->twig->render($template, $data);
        } else {
            $_template = $this->getTemplateDir() . $template;

            foreach ($data as $key => $value) {
                $$key = $value;
            }

            ob_start();
            include $_template;
            $result = ob_get_contents();
            ob_end_clean();

            return $result;
        }
    }

    private function getTwig()
    {
        $template_dir = $this->getTemplateDir();

        $loader = new \Twig_Loader_Filesystem($template_dir);

        $options = [];
        $template = $this->configuration->get("template");
        if (!empty($template["twig"]["cache"])) {
            $cache = $this->getCacheDir();

            $options["cache"] = $cache;
            $options["auto_reload"] = true;
        }

        $twig = new \Twig_Environment($loader, $options);

        $twig->addExtension(new \Twig_Extension_StringLoader());

        $this->registerBasicFunctions($twig);

        return $twig;
    }

    private function registerBasicFunctions(\Twig_Environment $twig)
    {
        $base64_encode = new \Twig_SimpleFunction('base64_encode', function ($string) {
            return base64_encode($string);
        });

        $file_get_contents = new \Twig_SimpleFunction('file_get_contents', function ($string) {
            return file_get_contents($string);
        });

        $md5 = new \Twig_SimpleFunction('md5', function ($string) {
            return md5($string);
        });

        $twig->addFunction($base64_encode);
        $twig->addFunction($file_get_contents);
        $twig->addFunction($md5);
    }

    private function getBasicVariables()
    {
        return [
            "_template_dir" => $this->getTemplateDir(),
            "_router" => $this->router,
            "_base_uri" => $this->getBaseUri()
        ];
    }

    private function getBaseUri(): string
    {
        if (!empty($_SERVER["HTTP_HOST"])) {
            return "//".$_SERVER["HTTP_HOST"].str_replace("index.php", "", $_SERVER["SCRIPT_NAME"]);
        }
        return "";
    }

    private function getTemplateDir()
    {
        $template = $this->configuration->get("template");
        $main_dir = $this->configuration->getDirectory() . "/../";

        return $main_dir . $template["dir"];
    }

    private function getCacheDir()
    {
        $template = $this->configuration->get("template");
        $main_dir = $this->configuration->getDirectory() . "/../";

        return $main_dir . $template["twig"]["cache"];
    }
}
