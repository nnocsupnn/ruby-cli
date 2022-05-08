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
        dump("Index Controller");
    }

    public function __set($name, $value)
    {
        $this->{$name} = $value;
    }

    function __sleep()
    {
        return [
            'tests'
        ];
    }
}