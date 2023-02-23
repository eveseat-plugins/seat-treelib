<?php

namespace RecursiveTree\Seat\TreeLib\Parser;

use RecursiveTree\Seat\TreeLib\Helpers\DynamicProperties;

class ParseResult
{
    use DynamicProperties;

    public $items;

    /**
     * @param $items Illuminate\Support\Collection
     */
    public function __construct($items)
    {
        $this->items = $items;
    }
}