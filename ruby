<?php

require_once "./vendor/autoload.php";

define('ROOT_PATH', __DIR__);

/**
 * Boot the application
 */
$app = new \Ruby\App;


define('APP_STARTED', time());
/**
 * Initialize all required
 * resource for the app
 */
$app->environment()->database()->run(
    // Passed all arguments passed on cli.
    clean_args($_SERVER['argv'], ",", "=")
);