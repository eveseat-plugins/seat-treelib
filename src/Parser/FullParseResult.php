<?php

namespace RecursiveTree\Seat\TreeLib\Parser;

use Illuminate\Support\Collection;

class FullParseResult extends ParseResult
{
    public Collection $unparsed;

    public function __construct($parsed, $unparsed)
    {
        $this->unparsed = $unparsed;
        parent::__construct($parsed);
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