<?php

namespace RecursiveTree\Seat\TreeLib\Parser;

use Exception;
use RecursiveTree\Seat\TreeLib\Items\EveItem;

abstract class Parser
{
    static function parseItems($text, string $EveItemClass = EveItem::class){
        $text = preg_replace('~\R~u', "\n", $text);

        //from specific to broad
        $parsers = [
            //fits
            FitParser::class,
            // the ingame multibuy
            MultibuyParser::class,
            // the old multibuy, but also support prices
            ManualBuyPrices::class,
            //also bytes on ingame multibuys, so handle it afterwards
            NewInventoryWindowParser::class,
            ItemListParser::class
        ];

        foreach ($parsers as $parser){
            $parsed = $parser::parse($text, $EveItemClass);
            if($parsed !== null) {
                //dd($parser, $parsed);
                $parsed->_debug_parser = $parser;
                return $parsed;
            }
        }

        $result = new ParseResult(collect());
        $result->warning = true;
        $result->_debug_parser = null;
        return $result;
    }

    protected abstract static function parse(string $text, string $EveItemClass);

    protected const BIG_NUMBER_REGEXP = "(?:\d+(?:[’ ]\d\d\d)*(?:\.\d\d)?)";

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
        return floatval(str_replace(["’"," "],"",$number));
    }
}