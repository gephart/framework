<?php

namespace Gephart\Framework\Line\Extension;

interface ExtensionInterface
{
    public function getAlign();
    public function getIcon();
    public function getTitle();
    public function getContent();
    public function getPriority();
}