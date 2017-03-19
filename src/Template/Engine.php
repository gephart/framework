<?php

namespace Gephart\Framework\Template;

use Gephart\Framework\Configuration\FrameworkConfiguration;

class Engine
{
    /**
     * @var FrameworkConfiguration
     */
    private $configuration;

    public function __construct(FrameworkConfiguration $configuration)
    {
        $this->configuration = $configuration;
    }

    public function render(string $template, array $data = []): string
    {
        $_template = $this->configuration->getDirectory() . "/../"
            . $this->configuration->get("template")["dir"]
            . $template;

        foreach ($data as $key => $value) {
            $$key = $value;
        }

        ob_start();
        include_once $_template;
        $result = ob_get_contents();
        ob_end_clean();

        return $result;
    }
}