<?php

class ScriptsTest extends \PHPUnit\Framework\TestCase
{

    public function testCopySkeleton()
    {
        ob_start();
        \Gephart\Framework\Composer\Scripts::$skeleton_dir = __DIR__ . "/../../skeleton";
        \Gephart\Framework\Composer\Scripts::$target_dir = __DIR__ . "/../cache/skeleton";
        \Gephart\Framework\Composer\Scripts::copySkeleton();
        ob_end_clean();

        $this->assertTrue(file_exists(__DIR__."/../cache/skeleton/config/framework.json"));
        $this->assertTrue(file_exists(__DIR__."/../cache/skeleton/cache/twig/.gitkeep"));
        $this->assertTrue(file_exists(__DIR__."/../cache/skeleton/src/App/Controller/DefaultController.php"));
    }

}