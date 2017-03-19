<?php

namespace Gephart\Framework\Debugging;

class Debugger
{
    public function __construct()
    {
        error_reporting(E_ALL);
        ini_set("display_error", 1);

        set_exception_handler([$this, "exceptionHandler"]);
        set_error_handler([$this, "errorHandler"]);//echo $adasd;
    }

    public function exceptionHandler(\Exception $exception)
    {
        echo $this->templateHead();
        echo <<<EOL
            <header>
                <h1>{$exception->getMessage()}</h1>
                <h2>{$exception->getFile()} at line <b>{$exception->getLine()}</b></h2>
            </header>
            <main>
EOL;
        echo "<table>";
        foreach ($exception->getTrace() as $trace) {
            $args = implode("\",\"",$trace["args"]);
            if ($args != "") {
                $args = "\"$args\"";
            }
            echo <<<EOL
                <tr>
                    <td>{$trace["class"]}::{$trace["function"]}($args)</td>
                    <td>{$trace["file"]} at line <b>{$trace["line"]}</b></td>
                </tr>
EOL;
        }
        echo "</table>";
        echo $this->templateFoot();
        exit;
    }

    public function errorHandler($errno, $errstr, $errfile, $errline)
    {
        switch ($errno) {
            case E_USER_ERROR:
            case E_ERROR:
                $type = "ERROR";
                break;

            case E_USER_WARNING:
            case E_WARNING:
                $type = "WARNING";
                break;

            case E_USER_NOTICE:
            case E_NOTICE:
                $type = "NOTICE";
                break;

            default:
                $type = "UNKNOWN";
        }
        echo $this->templateHead();
        echo <<<EOL
<header>
    <h1>{$type}: {$errstr}</h1>
    <h2>{$errfile} at line <b>{$errline}</b></h2>
</header>
<main>
EOL;
        echo $this->templateFoot();
        exit;
    }

    public function templateHead() {
        return <<<EOL
<!doctype html>
<html>
<head>
    <title>PROBLEM - Gephart - PHP framework</title>
    <link href="https://fonts.googleapis.com/css?family=Inconsolata:400,700|Raleway:100,100i,200,200i,300,300i,400,400i,500,500i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
        }
        body {
            font-family: "Raleway";
            color: #222;
        }
        code {
            font-family: "Inconsolata";
        }
        header {
            text-align: center;
            background: red;
            color: #fff;
            padding: 50px 24px;
            position: relative;
        }
        header:before {
            content: '';
            position: absolute;
            top:0;
            left:0;
            width:100%;
            height:100%;
            background: rgba(0,0,0,.5);
        }
        main {
            padding: 24px;
            max-width: 900px;
            margin: 0 auto;
        }
        h1,h2 {
            position: relative;
        }
        h1 {
            font-size: 46px;
            font-weight: 900;
            margin-bottom: 24px;
        }
        h2 {
            font-weight: 100;
            font-size: 24px
        }
        table {
            border-collapse: collapse;
            border-top: 1px #ddd solid;
            border-left: 1px #ddd solid;
        }
        td {
            border-right: 1px #ddd solid;
            border-bottom: 1px #ddd solid;
            padding: 5px 10px;
            font-size: 14px;
        }
    </style>
</head>
<body>
EOL;
    }

    public function templateFoot()
    {
        return <<<EOL
            </main>
            </body>
        </html>
EOL;
    }
}