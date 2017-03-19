<?php

namespace Gephart\Framework\Composer;

class Scripts
{
    public static function install()
    {
        self::copySkeleton();
    }

    public static function copySkeleton()
    {
        $from = __DIR__ . "/../../skeleton";
        $to = ".";

        self::copyDir($from, $to);
    }

    public static function copyDir($from, $to)
    {
        if (!is_dir($from) || !is_dir($to)) {
            return false;
        }

        if ($handle = opendir($from)) {
            while (false !== ($entry = readdir($handle))) {
                if ($entry == "." || $entry == "..") continue;

                if (is_dir($from . "/" . $entry)) {
                    @mkdir($to . "/" . $entry);
                    echo "Create directory: ". $to . "/" . $entry . PHP_EOL;
                    self::copyDir($from . "/" . $entry, $to  . "/" . $entry);
                } else {
                    $file = file_get_contents($from . "/" . $entry);
                    file_put_contents($to . "/" . $entry, $file);
                    echo "Create file: ". $to . "/" . $entry . PHP_EOL;
                }
            }
        }
    }
}