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

    date_default_timezone_set(getenv('TIMEZONE'));

    if ($isDebug == "true") {
        ini_set('display_errors', '1');
        error_reporting(E_ALL);
    } else {
        ini_set('display_errors', '0');
        error_reporting(E_ERROR);
    }
}

function get($url, $isVerbose = true):?object {
    $ch = curl_init();
    // set url
    curl_setopt($ch, CURLOPT_URL, $url);

    //return the transfer as a string
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

    curl_setopt($ch, CURLOPT_VERBOSE, $isVerbose);

    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/80.0.3987.149 Safari/537.36');

    // $output contains the output string
    $output = curl_exec($ch);

    $response_data = curl_getinfo($ch);

    // close curl resource to free up system resources
    curl_close($ch); 

    return (object) [
        'response' => $output,
        'curl_response' => $response_data
    ];
}


function clean_args($args, $separator = ",", $sticky = "="):array 
{
    unset($args[0]);
    $opts = [];
    
    $params = explode($separator, $args[1]);
    foreach ($params as $k => $request) {
        if (strpos($request, $sticky) === -1) {
            dd('Invalid paramter provided.');
        } else {
            if (strpos($request, $sticky) <= 0) {
                dump("Invalid argument.");
                continue;
            }

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


function checkRoute($args, $routes):?object
{
    try 
    {
        $base = getenv('BASE_NS');
        $callables = [];
        foreach ($args as $arg => $methods) 
        {
            $requestMethod = current($methods);
            if (@$routes[$arg]) 
            {
                list($controller, $method) = explode("@", $routes[$arg]);

                $class = $base . $controller;
                $method = $requestMethod;
                if (class_exists($class)) 
                {
                    $classInstance = new $class;
                    if (method_exists($classInstance, $method)) 
                    {
                        $callables[$controller]['class'] = ($classInstance);
                        $callables[$controller]['method'] = $method;

                        $callables[$controller] = (object) $callables[$controller];
                    }
                    else
                    {
                        throw new \Exception("Method $method on Class $class registered not exists");
                    }
                } 
                else 
                {
                    throw new \Exception("Class $base registered not exists");
                }
            }
        }

        return (object) $callables;
    } 
    catch (\Exception $e) 
    {
        dump($e->getMessage());
        return (object) $callables;
    }
}

function outputLog ($str = "")
{
    $tmstamp = date('Y-m-d H:i:s');
    echo "[$tmstamp] $str" . PHP_EOL;
    return;
}