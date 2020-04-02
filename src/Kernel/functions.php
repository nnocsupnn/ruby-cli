<?php

/**
 * Global functions
 */

use Symfony\Component\Dotenv\Dotenv;

function init():void {
    $file = dirname(dirname(__DIR__)) . DIRECTORY_SEPARATOR . '.env';
    
    $dotenv = new Dotenv(true);
    $dotenv->load($file);
    
    $isDebug = getenv('DEBUG');

    if ($isDebug == "true") {
        ini_set('display_errors', '1');
        error_reporting(E_ALL);
    } else {
        ini_set('display_errors', '0');
        error_reporting(E_ERROR);
    }
}

function get($url):array {
    $ch = curl_init();
    // set url
    curl_setopt($ch, CURLOPT_URL, $url);

    //return the transfer as a string
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    // $output contains the output string
    $output = curl_exec($ch);

    $response_data = curl_getinfo($ch);

    // close curl resource to free up system resources
    curl_close($ch); 

    return [
        'response' => $output,
        'curl_response' => $response_data
    ];
}


function cleanCliArgs ($args):array {
    unset($args[0]);
    $opts = [];
    
    $params = explode(",", $args[1]);
    foreach ($params as $k => $request) {
        if (strpos($request, '=') === -1) {
            dd('Invalid paramter provided.');
        } else {
            list($k, $v) = explode("=", $request);
            if (!empty($opts[$k])) {
                array_push($opts[$k], $v);
            } else {
                $opts[$k][] = $v;
            }
        }
    }

    return $opts;
}


function checkRoute ($args, $routes)
{
    try {
        $base = getenv('BASE_NS');
        $callables = [];
        foreach ($args as $arg => $methods) {
            $requestMethod = current($methods);
            if ($routes[$arg]) {
                list($controller, $method) = explode("@", $routes[$arg]);

                $class = $base . $controller;
                $method = $requestMethod;
                if (class_exists($class)) {
                    $classInstance = new $class;
                    if (method_exists($classInstance, $method)) {
                        $callables[$controller]['instance'] = ($classInstance);
                        $callables[$controller]['method'] = $method;
                    } else {
                        throw new \Exception("Method $method on Class $class registered not exists");
                    }
                } else {
                    throw new \Exception("Class $base registered not exists");
                }
            }
        }
        return $callables;
    } catch (\Exception $e) {
        dump($e->getMessage());
        return $callables;
    }
}