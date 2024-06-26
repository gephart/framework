<?php

namespace Gephart\Framework\Line\Extension;

use Gephart\Configuration\Configuration;
use Gephart\Quality\Checker;
use Gephart\Quality\Entity\ClassQuality;

class QualityExtension implements ExtensionInterface
{

    /**
     * @var Checker
     */
    private $quality_checker;

    /**
     * @var string
     */
    private $cache_dir;

    public function __construct(Checker $quality_checker, Configuration $configuration)
    {
        $this->quality_checker = $quality_checker;

        $root_dir = realpath($configuration->getDirectory() . "/../");

        $this->quality_checker->setDir($root_dir . "/src");
        $this->cache_dir = $root_dir . "/cache";
    }

    public function getAlign()
    {
        return "right";
    }

    public function getTitle()
    {
        $classes_quality = $this->fromCache("classes_quality.ini", function () {
            return $this->quality_checker->getQuality();
        });

        $quality = $this->getWorstQuality($classes_quality);
        return "Quality: <strong>" . $quality . "%</strong>";
    }

    public function getContent()
    {
        $classes_quality = $this->fromCache("classes_quality.ini", function () {
            return $this->quality_checker->getQuality();
        });

        $content = "
            <table>
                <tr>
                    <th>Class</th>
                    <th>Issues</th>
                    <th>Quality</th>
                </tr>
        ";

        foreach ($classes_quality as $class_quality) {
            $issues = $class_quality->getIssues();

            if (count($issues) === 0) {
                continue;
            }

            $issues_list = "";
            foreach ($issues as $issue) {
                $name = $issue->getName();
                $metric = $issue->getMetric();
                $type = $issue->getType();
                $expected = $issue->getExpected();
                $given = $issue->getGiven();

                if ($type == "method") {
                    $type .= " <em>$name</em>";
                }

                $issues_list .= "<div style='white-space:nowrap'><strong>$metric</strong> of <strong>$type</strong> should be maximally <strong>$expected</strong>, given <strong>$given</strong>.</div>";
            }

            $content .= "
                <tr>
                    <td>".$class_quality->getClassName()."</td>
                    <td>$issues_list</td>
                    <td>".$class_quality->getPercent()."%</td>
                </tr>
            ";
        }

        $content .= "</table>";

        if (isset($issues_list)) {
            return $content;
        }

        return $content;
    }

    public function getIcon()
    {
        return "data:image/svg+xml;base64,".base64_encode(trim("
            <?xml version=\"1.0\" encoding=\"iso-8859-1\"?>
            <!-- Generator: Adobe Illustrator 19.0.0, SVG Export Plug-In . SVG Version: 6.00 Build 0)  -->
            <svg xmlns=\"http://www.w3.org/2000/svg\" xmlns:xlink=\"http://www.w3.org/1999/xlink\" version=\"1.1\" id=\"Layer_1\" x=\"0px\" y=\"0px\" viewBox=\"0 0 512 512\" style=\"enable-background:new 0 0 512 512;\" xml:space=\"preserve\" width=\"512px\" height=\"512px\">
            <g>
                <g>
                    <path d=\"M493.563,431.87l-58.716-125.913c-32.421,47.207-83.042,80.822-141.639,91.015l49.152,105.401    c6.284,13.487,25.732,12.587,30.793-1.341l25.193-69.204l5.192-2.421l69.205,25.193    C486.63,459.696,499.839,445.304,493.563,431.87z\" fill=\"#FFFFFF\"/>
                </g>
            </g>
            <g>
                <g>
                    <path d=\"M256.001,0C154.815,0,72.485,82.325,72.485,183.516s82.331,183.516,183.516,183.516    c101.186,0,183.516-82.325,183.516-183.516S357.188,0,256.001,0z M345.295,170.032l-32.541,31.722l7.69,44.804    c2.351,13.679-12.062,23.956-24.211,17.585l-40.231-21.148l-40.231,21.147c-12.219,6.416-26.549-3.982-24.211-17.585l7.69-44.804    l-32.541-31.722c-9.89-9.642-4.401-26.473,9.245-28.456l44.977-6.533l20.116-40.753c6.087-12.376,23.819-12.387,29.913,0    l20.116,40.753l44.977,6.533C349.697,143.557,355.185,160.389,345.295,170.032z\" fill=\"#FFFFFF\"/>
                </g>
            </g>
            <g>
                <g>
                    <path d=\"M77.156,305.957L18.44,431.87c-6.305,13.497,7.023,27.81,20.821,22.727l69.204-25.193l5.192,2.421l25.193,69.205    c5.051,13.899,24.496,14.857,30.793,1.342l49.152-105.401C160.198,386.779,109.578,353.165,77.156,305.957z\" fill=\"#FFFFFF\"/>
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
        return 15;
    }

    /**
     * @param ClassQuality[] $classes_quality
     * @return int
     */
    private function getWorstQuality(array $classes_quality): int
    {
        $worst_quality = 100;

        foreach ($classes_quality as $class_quality) {
            $quality = $class_quality->getPercent();

            if ($quality < $worst_quality) {
                $worst_quality = $quality;
            }
        }

        return $worst_quality;
    }

    /**
     * @param string $file
     * @param callable $param
     * @return ClassQuality[]
     */
    private function fromCache(string $file, callable $param)
    {
        $cache_file = $this->cache_dir . "/" . $file;
        if (file_exists($cache_file)) {
            return unserialize(file_get_contents($cache_file));
        }

        $data = $param();
        file_put_contents($cache_file, serialize($data));

        return $data;
    }
}
