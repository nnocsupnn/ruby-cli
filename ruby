<?php

require_once "./vendor/autoload.php";

$app = new \Ruby\App;
$app->environment()
->database()
->run(cleanCliArgs($_SERVER['argv']));