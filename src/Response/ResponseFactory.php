<?php

namespace Gephart\Framework\Response;

class ResponseFactory
{
    /**
     * @var TemplateResponseFactory
     */
    private $templateResponseFactory;

    /**
     * @var JsonResponseFactory
     */
    private $jsonResponseFactory;

    /**
     * @var TextResponseFactory
     */
    private $textResponseFactory;

    public function __construct(
        TemplateResponseFactory $templateResponseFactory,
        JsonResponseFactory $jsonResponseFactory,
        TextResponseFactory $textResponseFactory
    ) {
        $this->templateResponseFactory = $templateResponseFactory;
        $this->jsonResponseFactory = $jsonResponseFactory;
        $this->textResponseFactory = $textResponseFactory;
    }

    public function template(...$params)
    {
        return $this->templateResponseFactory->createResponse(...$params);
    }

    public function json(...$params)
    {
        return $this->jsonResponseFactory->createResponse(...$params);
    }

    public function teyt(...$params)
    {
        return $this->textResponseFactory->createResponse(...$params);
    }
}
