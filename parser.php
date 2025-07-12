<?php

require_once "vendor/autoload.php";

use \Lewla\ProductProcessor\Handler\Application;

$args = getopt('', ['file:', 'unique-combinations:']);

try {
    $app = new Application();
    $app->run($args);
} catch (RuntimeException $e){
    echo 'Error: ' . $e->getMessage();
}
