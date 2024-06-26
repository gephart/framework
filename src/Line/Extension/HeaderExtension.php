<?php

namespace Gephart\Framework\Line\Extension;

class HeaderExtension implements ExtensionInterface
{
    public function getAlign()
    {
        return "right";
    }

    public function getTitle()
    {
        return "Headers (<strong>".http_response_code()."</strong>)";
    }

    public function getContent()
    {
        flush();

        $content = "<table>";

        foreach (headers_list() as $header) :
            $content .= "
                <tr>
                    <td>".explode(":", $header)[0]."</td>
                    <td>".explode(":", $header)[1]."</td>
                </tr>
            ";
        endforeach;
        $content .= "</table>";

        return $content;
    }

    public function getIcon()
    {
        return "data:image/svg+xml;base64,".base64_encode(trim("
            <?xml version=\"1.0\" encoding=\"iso-8859-1\"?>
            <!-- Generator: Adobe Illustrator 16.0.0, SVG Export Plug-In . SVG Version: 6.00 Build 0)  -->
            <!DOCTYPE svg PUBLIC \"-//W3C//DTD SVG 1.1//EN\" \"http://www.w3.org/Graphics/SVG/1.1/DTD/svg11.dtd\">
            <svg xmlns=\"http://www.w3.org/2000/svg\" xmlns:xlink=\"http://www.w3.org/1999/xlink\" version=\"1.1\" id=\"Capa_1\" x=\"0px\" y=\"0px\" width=\"512px\" height=\"512px\" viewBox=\"0 0 35 35\" style=\"enable-background:new 0 0 35 35;\" xml:space=\"preserve\">
            <g>
                <g>
                    <path d=\"M0,25.366h35V9.546H0V25.366z M2.121,11.667h30.758v11.578H2.121V11.667z\" fill=\"#FFFFFF\"/>
                    <rect y=\"28.283\" width=\"35\" height=\"6.717\" fill=\"#FFFFFF\"/>
                    <rect width=\"35\" height=\"6.717\" fill=\"#FFFFFF\"/>
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
        return 200;
    }
}
