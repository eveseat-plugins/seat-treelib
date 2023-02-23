<?php

namespace RecursiveTree\Seat\TreeLib\Parser;

abstract class Parser
{
    static function parseItems($text){
        $text = preg_replace('~\R~u', "\n", $text);

        //from specific to broad
        $parsers = [
            MultibuyParserWithPrice::class,
            MultibuyParser::class
        ];

        foreach ($parsers as $parser){
            $parsed = $parser::parse($text);
            if($parsed !== null) {
                return $parsed;
            }
        }

        return null;
    }

    protected abstract static function parse($text);
}