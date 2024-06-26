<?php

namespace Gephart\Framework\Line\Extension;

use Gephart\EventManager\EventManager;

class ListenersExtension implements ExtensionInterface
{
    /**
     * @var EventManager
     */
    private $event_manager;

    public function __construct(EventManager $event_manager)
    {
        $this->event_manager = $event_manager;
    }

    public function getAlign()
    {
        return "right";
    }

    public function getTitle()
    {
        return "Listeners";
    }

    public function getContent()
    {
        $listeners = $this->event_manager->getListeners();

        $content = "
            <table>
                <tr>
                    <th>Event</th>
                    <th>Callback</th>
                    <th>Priority</th>
                </tr>
        ";

        foreach ($listeners as $listener) :
            $content .= "
                <tr>
                    <td>".$listener["event"]."</td>
                    <td>";

            if (is_array($listener["callback"])) {
                $content .= get_class($listener["callback"][0]) . "::" . $listener["callback"][1];
            }

            $content .= "</td>
                    <td>".$listener["priority"]."</td>
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
            <!-- Generator: Adobe Illustrator 19.0.0, SVG Export Plug-In . SVG Version: 6.00 Build 0)  -->
            <svg xmlns=\"http://www.w3.org/2000/svg\" xmlns:xlink=\"http://www.w3.org/1999/xlink\" version=\"1.1\" id=\"Layer_1\" x=\"0px\" y=\"0px\" viewBox=\"0 0 330 330\" style=\"enable-background:new 0 0 330 330;\" xml:space=\"preserve\" width=\"512px\" height=\"512px\">
            <g id=\"XMLID_19_\">
                <path id=\"XMLID_20_\" d=\"M246.504,35.833C224.202,12.726,195.257,0,165,0C101.589,0,50,51.589,50,115c0,8.284,6.716,15,15,15   c8.284,0,15-6.716,15-15c0-46.869,38.131-85,85-85c48.649,0,85,44.876,85,85c0,44.866-48.145,116.704-66.779,140.712   C168.75,273.793,149.653,294.946,142.31,300H105c-8.284,0-15,6.716-15,15s6.716,15,15,15h39.998c0.001,0,0.001,0,0.003,0   c8.385,0,17.229-4.777,39.898-29.965c11.41-12.679,21.394-25.141,21.814-25.665c0.042-0.053,0.085-0.107,0.127-0.161   C209.828,270.368,280,179.407,280,115C280,86.744,267.791,57.889,246.504,35.833z\" fill=\"#FFFFFF\"/>
                <path id=\"XMLID_21_\" d=\"M125,220c-8.284,0-15,6.716-15,15s6.716,15,15,15c24.813,0,45-20.186,45-45   c0-19.555-12.542-36.227-30-42.42V115c0-13.785,11.215-25,25-25c13.785,0,25,11.215,25,25c0,8.284,6.716,15,15,15   c8.284,0,15-6.716,15-15c0-30.327-24.673-55-55-55c-30.327,0-55,24.673-55,55v60c0,8.284,6.716,15,15,15c8.271,0,15,6.729,15,15   S133.271,220,125,220z\" fill=\"#FFFFFF\"/>
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
        return 50;
    }
}
