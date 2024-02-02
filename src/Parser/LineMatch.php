<?php

namespace RecursiveTree\Seat\TreeLib\Parser;

class LineMatch
{
    public string $line;
    public PREGMatch $match;

    /**
     * @param $line string
     * @param $match PREGMatch
     */
    public function __construct(string $line, PREGMatch $match)
    {
        $this->line = $line;
        $this->match = $match;
    }
}