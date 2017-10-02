<?php

namespace Gephart\Framework\Line\Extension;

class TimerExtension implements ExtensionInterface
{
    /**
     * @var float
     */
    private $microtime;

    public function __construct()
    {
        $this->microtime = microtime(true);
    }

    public function getAlign()
    {
        return "right";
    }

    public function getTitle()
    {
        return "Time: <strong>" . number_format((microtime(true) - $this->microtime) * 1000, 0, " ", ".") . "ms</strong>";
    }

    public function getContent()
    {
        return false;
    }

    public function getIcon()
    {
        return "data:image/svg+xml;base64," . base64_encode(trim("
            <?xml version=\"1.0\" encoding=\"iso-8859-1\"?>
            <!-- Generator: Adobe Illustrator 16.0.0, SVG Export Plug-In . SVG Version: 6.00 Build 0)  -->
            <!DOCTYPE svg PUBLIC \"-//W3C//DTD SVG 1.1//EN\" \"http://www.w3.org/Graphics/SVG/1.1/DTD/svg11.dtd\">
            <svg xmlns=\"http://www.w3.org/2000/svg\" xmlns:xlink=\"http://www.w3.org/1999/xlink\" version=\"1.1\" id=\"Capa_1\" x=\"0px\" y=\"0px\" width=\"512px\" height=\"512px\" viewBox=\"0 0 97.16 97.16\" style=\"enable-background:new 0 0 97.16 97.16;\" xml:space=\"preserve\">
            <g>
                <g>
                    <path d=\"M48.58,0C21.793,0,0,21.793,0,48.58s21.793,48.58,48.58,48.58s48.58-21.793,48.58-48.58S75.367,0,48.58,0z M48.58,86.823    c-21.087,0-38.244-17.155-38.244-38.243S27.493,10.337,48.58,10.337S86.824,27.492,86.824,48.58S69.667,86.823,48.58,86.823z\" fill=\"#FFFFFF\"/>
                    <path d=\"M73.898,47.08H52.066V20.83c0-2.209-1.791-4-4-4c-2.209,0-4,1.791-4,4v30.25c0,2.209,1.791,4,4,4h25.832    c2.209,0,4-1.791,4-4S76.107,47.08,73.898,47.08z\" fill=\"#FFFFFF\"/>
                </g>
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
        return 0;
    }
}
