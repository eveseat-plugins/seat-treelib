<?php

namespace RecursiveTree\Seat\TreeLib\Helpers;

trait DynamicProperties
{
    private $properties = [];

    protected function setProperties($properties){
        $this->properties = $properties;
    }

    public function __set($name, $value)
    {
        $this->properties[$name] = $value;
    }

    public function __get($name){
        return $this->properties[$name] ?? null;
    }
}