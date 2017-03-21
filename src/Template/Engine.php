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
        if ($this->twig && substr($template,-5) == ".twig") {
            return $this->twig->render($template, $data);
        } else {
            $_template = $this->configuration->getDirectory() . "/../"
                . $this->configuration->get("template")["dir"]
                . $template;

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
        $template = $this->configuration->get("template");
        $main_dir = $this->configuration->getDirectory() . "/../";

        $template_dir = $main_dir . $template["dir"];

        $loader = new \Twig_Loader_Filesystem($template_dir);

        $options = [];
        if (!empty($template["twig"]["cache"])) {
            $cache = $main_dir . $template["twig"]["cache"];

            $options["cache"] = $cache;
            $options["auto_reload"] = true;
        }

        return new \Twig_Environment($loader, $options);
    }
}