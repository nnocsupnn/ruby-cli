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
        include "routes";
        $calls = checkRoute($args, $routes);
        foreach ($calls as $className => $container) {
            outputLog("Running $className:" . $container->method);
            $container->class->{$container->method}();
            outputLog("Done.");
        }

        return;
    } 
}