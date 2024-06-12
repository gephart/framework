<?php

namespace Gephart\Framework\Composer;

class Scripts
{
    public static $skeleton_dir;
    public static $target_dir;

    public static function install()
    {
        self::$skeleton_dir = __DIR__ . "/../../skeleton";
        self::$target_dir = ".";

        self::copySkeleton();
    }

    public static function copySkeleton()
    {
        self::copyDir(self::$skeleton_dir, self::$target_dir);
    }

    public static function copyDir($from, $to)
    {
        if (!is_dir($from) || !is_dir($to)) {
            return false;
        }

        if ($handle = opendir($from)) {
            while (false !== ($entry = readdir($handle))) {
                if ($entry == "." || $entry == "..") {
                    continue;
                }

                if (is_dir($from . "/" . $entry)) {
                    @mkdir($to . "/" . $entry, 0777);
                    echo "Create directory: ". $to . "/" . $entry . PHP_EOL;
                    self::copyDir($from . "/" . $entry, $to  . "/" . $entry);
                } elseif (!file_exists($to . "/" . $entry)) {
                    $file = file_get_contents($from . "/" . $entry);
                    file_put_contents($to . "/" . $entry, $file);
                    echo "Create file: ". $to . "/" . $entry . PHP_EOL;
                }
            }
        }
    }
}
