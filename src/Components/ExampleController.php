<?php

namespace Ruby\Components;

class ExampleController
{
    public function __call($name, $args)
    {
        dd($name, $args);
    }

    public function index()
    {
        dd([__FUNCTION__]);
    }
}