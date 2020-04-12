<?php

namespace Ruby\Components;

class ExampleController
{
    public $var;
    public function __call($name, $args)
    {
        dump($name, $args);
    }

    public function index()
    {
        dump([__FUNCTION__]);
    }

    public function __set($name, $value)
    {
        $this->var = $value;
    }

    function __sleep()
    {
        return [
            'tests'
        ];
    }
}