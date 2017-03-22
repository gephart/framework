<?php

namespace Gephart\Framework\Template;

use Gephart\Framework\Configuration\FrameworkConfiguration;

class Engine
{
    /**
     * @var FrameworkConfiguration
     */
    private $configuration;

    /**
     * @var \Twig_Environment
     */
    private $twig;

    public function __construct(FrameworkConfiguration $configuration)
    {
        $this->configuration = $configuration;

        $template = $this->configuration->get("template");
        if (isset($template["twig"])) {
            $this->twig = $this->getTwig();
        }
    }

    public function render(string $template, array $data = []): string
    {
        $data = array_merge($this->getBasicVariables(), $data);

        if ($this->twig && substr($template,-5) == ".twig") {
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
        if (!empty($template["twig"]["cache"])) {
            $cache = $main_dir . $template["twig"]["cache"];

            $options["cache"] = $cache;
            $options["auto_reload"] = true;
        }

        $twig = new \Twig_Environment($loader, $options);

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

        $twig->addFunction($base64_encode);
        $twig->addFunction($file_get_contents);
    }

    private function getBasicVariables()
    {
        return [
            "_template_dir" => $this->getTemplateDir()
        ];
    }

    private function getTemplateDir()
    {
        $template = $this->configuration->get("template");
        $main_dir = $this->configuration->getDirectory() . "/../";

        return $main_dir . $template["dir"];
    }
}