<?php

namespace Ruby;
use Ruby\Kernel\Database;
use Exception as ShitHereWeGoAgain;

defined('ROOT_PATH') or die('Application could not run on ' . ROOT_PATH);

class App {
    
    private $functions;

    public function environment () 
    {
        init();
        return $this;
    }


    public function database() 
    {
        (new Database)->load();
        return $this;
    }
    

    /**
     * Start application
     */
    public function run($args = []) 
    {
        /**
         * @goto routes 
         * set options and the value is the controller name.
         */
        include "routes";

        $assocOpt = [];
        $strOpt = "";
        foreach ($routes as $k => $route) 
        {
            /**
             * php ruby --routeName methodName
             */
            $strOpt .= substr($k, 0, 1) . ":";
            array_push($assocOpt, "$k:");
            $opt = getopt($strOpt, $assocOpt);
            if (!@$opt[$k]) 
            {
                outputLog("Checking...");
                continue;
            }

            $class = getenv('BASE_NS') . $route;
            
            if (!array_key_exists($k, $opt) 
            || !method_exists($class, $opt[$k])) throw new ShitHereWeGoAgain('Method not exists');

            outputLog("Running $route:" . $opt[$k]);
            if (method_exists($class, $opt[$k])) (new $class)->{$opt[$k]}();
            outputLog("Done.");
        }

        return;
    }
}