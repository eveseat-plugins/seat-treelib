<?php

namespace RecursiveTree\Seat\TreeLib\Parser;

use Illuminate\Support\Collection;
use JsonSerializable;
use RecursiveTree\Seat\TreeLib\Helpers\DynamicProperties;

class ParseResult implements JsonSerializable
{
    use DynamicProperties;

    public Collection $items;

    public Collection $unparsed;

    public function __construct($items, $unparsed)
    {
        $this->items = $items;
        $this->unparsed = $unparsed;
    }

    public function __serialize(): array
    {
        return $this->getProperties();
    }

    public function jsonSerialize(): array
    {
        return array_merge($this->getProperties(), [
            "items" => $this->items,
            "unparsed" => $this->unparsed
        ]);
    }
}