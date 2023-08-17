<?php

namespace RecursiveTree\Seat\TreeLib\Parser;

use Illuminate\Support\Collection;
use RecursiveTree\Seat\TreeLib\Helpers\DynamicProperties;

class ParseResult implements \JsonSerializable
{
    use DynamicProperties;

    public Collection $items;


    public function __construct($items)
    {
        $this->items = $items;
    }

    public function __serialize(): array
    {
        return $this->getProperties();
    }

    public function jsonSerialize()
    {
        return array_merge($this->getProperties(),[
            "items"=>$this->items,
        ]);
    }
}