<?php

namespace Ruby;
use Ruby\Kernel\Database;
use Exception as ShitHereWeGoAgain;

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
    public function run($args) 
    {
        include "routes";
        $calls = checkRoute($args, $routes);
        foreach ($calls as $className => $instance) {
            $instance['instance']->{$instance['method']}();
        }
    } 
}