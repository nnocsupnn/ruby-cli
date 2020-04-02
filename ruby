<?php

require_once "./vendor/autoload.php";

define('ROOT_PATH', __DIR__);

$app = new \Ruby\App;
$app->environment()
->database()
->run(cleanCliArgs($_SERVER['argv']));