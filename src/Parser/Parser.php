<?php

namespace RecursiveTree\Seat\TreeLib\Parser;

use Exception;

abstract class Parser
{
    static function parseItems($text){
        $text = preg_replace('~\R~u', "\n", $text);

        //from specific to broad
        $parsers = [
            FitParser::class,
            MultibuyParser::class,
            // Cap Booster 400 200 can be understood as 400x Cap Booster at 200 ISK or 200x Cap Booster 400, where the second one is correct. This means the normal multibuy parser mus run first.
            MultibuyWithPriceParser::class,
            //also bytes on ingame multibuys, so handle it afterwards
            NewInventoryWindowParser::class,
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

    protected const BIG_NUMBER_REGEXP = "(?:\d+(?:’\d\d\d)*(?:\.\d\d)?)";

    protected static function match($expr, $text){
        $result = preg_match("/$expr/",$text, $match);
        if($result === false){
            throw new Exception("regexp error");
        }
        if ($result===0) return null;
        return new PREGMatch($match);
    }

    protected static function matchLines($expr,$input){
        $lines = collect(explode("\n",$input));

        $lines = $lines->map(function ($line) use ($expr) {
            $match = self::match($expr, $line);
            return new LineMatch($line,$match);
        });

        return $lines;
    }

    protected static function parseBigNumber($number){
        if($number === null) return null;
        return floatval(str_replace("’","",$number));
    }
}