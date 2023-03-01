<?php

namespace RecursiveTree\Seat\TreeLib\Parser;

class LineMatch
{
    public $line;
    public $match;

    /**
     * @param $line string
     * @param $match PREGMatch
     */
    public function __construct($line, $match)
    {
        $this->line = $line;
        $this->match = $match;
    }
}