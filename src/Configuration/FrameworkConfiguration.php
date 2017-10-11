<?php

namespace Gephart\Framework\Configuration;

use Gephart\Configuration\Configuration;

class FrameworkConfiguration
{
    /**
     * @var array
     */
    private $framework;

    /**
     * @var string
     */
    private $directory;

    public function __construct(Configuration $configuration)
    {
        try {
            $framework = $configuration->get("framework");

            if (!is_array($framework)) {
                $framework = [];
            }
        } catch (\Exception $e) {
            $framework = [];
        }

        $this->framework = $framework;
        $this->directory = $configuration->getDirectory();
    }

    public function get(string $key)
    {
        return isset($this->framework[$key]) ? $this->framework[$key] : false;
    }

    public function getDirectory(): string
    {
        return $this->directory;
    }
}
