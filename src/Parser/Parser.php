<?php

namespace RecursiveTree\Seat\TreeLib\Parser;

abstract class Parser
{
    static function parseItems($text){
        $text = preg_replace('~\R~u', "\n", $text);

        //from specific to broad
        $parsers = [
            FitParser::class,
            InventoryWindowParser::class,
            MultibuyParser::class,
            // Cap Booster 400 200 can be understood as 400x Cap Booster at 200 ISK or 200x Cap Booster 400, where the second one is correct. This means the normal multibuy parser mus run first.
            MultibuyWithPriceParser::class,
            ItemListParser::class
        ];

        foreach ($parsers as $parser){
            $parsed = $parser::parse($text);
            if($parsed !== null) {
                //dd($parser, $parsed);
                return $parsed;
            }
        }

        return null;
    }

    protected abstract static function parse($text);
}