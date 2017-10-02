<?php

namespace Gephart\Framework\Response;

use Gephart\Framework\Configuration\FrameworkConfiguration;
use Gephart\Framework\Template\Engine;
use Gephart\Response\ResponseInterface;

class TemplateResponse implements ResponseInterface
{

    /**
     * @var string
     */
    private $template;

    /**
     * @var array
     */
    private $data;

    /**
     * @var Engine
     */
    private $engine;

    public function __construct(Engine $engine)
    {
        $this->engine = $engine;
    }

    public function template(string $template, array $data = [])
    {
        $this->template = $template;
        $this->data = $data;

        return $this;
    }

    public function render()
    {
        return $this->engine->render($this->template, $this->data);
    }
}
