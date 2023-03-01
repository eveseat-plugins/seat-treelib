<?php

namespace RecursiveTree\Seat\TreeLib\Parser;

use RecursiveTree\Seat\TreeLib\Helpers\DynamicProperties;

class PREGMatch
{
 use DynamicProperties;


    public function __construct($match)
    {
        $this->setProperties($match);
    }
}