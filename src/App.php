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
        try {
            foreach ($routes as $k => $route) 
            {
                /**
                 * php ruby --{routeName} methodName
                 */
                $strOpt .= substr($k, 0, 1) . ":";
                array_push($assocOpt, "$k:");
                

                $opt = getopt(null, $assocOpt);
                if (!@$opt[$k]) 
                {
                    outputLog("Invalid {$k} Command skipping..");
                    continue;
                }

                $class = getenv('BASE_NS') . $route;

                if (!class_exists($class)) throw new ShitHereWeGoAgain("Class " . $class . "  not exists");
                if (!array_key_exists($k, $opt)) throw new ShitHereWeGoAgain("Method " . $k . " not exists");

                $class = new $class;

                outputLog("Running $route:" . $opt[$k]);
                $class->{$opt[$k]}();
            }
        } catch (ShitHereWeGoAgain $sht) {
            outputLog($sht->getMessage());
        } finally {
            outputLog("Done.");
        }

        return;
    }
}