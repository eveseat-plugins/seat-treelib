<?php

namespace RecursiveTree\Seat\TreeLib\Parser;

use Exception;
use Illuminate\Support\Collection;
use RecursiveTree\Seat\TreeLib\Items\EveItem;

abstract class Parser
{
    public static array $parsers = [
        //fits
        FitParser::class,
        // the ingame multibuy
        MultibuyParser::class,
        // the old multibuy, but also support prices
        ManualBuyPrices::class,
        //also bytes on ingame multibuys, so handle it afterwards
        NewInventoryWindowParser::class,
        ItemListParser::class,
        // Try to parse a mineral scan if nothing else matched
        MineralScanParser::class
    ];

    /**
     * Use this function to add a new parser to the list of default parsers
     * @param string $parserClassName
     * @return void
     */
    public static function registerParser(string $parserClassName): void
    {
        self::$parsers[] = $parserClassName;
    }

    static function parseItems($text, string $EveItemClass = EveItem::class, $parsers = []): ParseResult
    {
        $text = preg_replace('~\R~u', "\n", $text);
        // Replace where volume is displayed using the cubic character instead of m3
        $text = preg_replace('~\x{b3}~u', '3', $text);
        // Replace weird non breaking spaces with normal whitespaces
        $text = preg_replace('/\xc2\xa0/', ' ', $text);

        // Use default parsers if none are specified by the user.
        if (count($parsers) === 0) {
            //from specific to broad
            $parsers = self::$parsers;
        }

        foreach ($parsers as $parser) {
            $parsed = $parser::parse($text, $EveItemClass);
            if ($parsed !== null) {
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

    /**
     * Use 3 kind of separators for thousands : ' , and whitespace
     * Added different kind of decimals separators as well : . and ,
     */
    protected const BIG_NUMBER_REGEXP = "(?:\d+(?:[’\s+,]\d\d\d)*(?:[\.,]\d\d)?)";

    /**
     * @throws Exception
     */
    protected static function match($expr, $text): ?PREGMatch
    {
        $result = preg_match("/$expr/", $text, $match);
        if ($result === false) {
            throw new Exception("regexp error");
        }
        if ($result === 0) return null;
        return new PREGMatch($match);
    }

    protected static function matchLines($expr, $input): Collection
    {
        $lines = collect(explode("\n", $input));

        return $lines->map(function ($line) use ($expr) {
            $match = self::match($expr, $line);
            return new LineMatch($line, $match);
        });
    }

    protected static function parseBigNumber($number): ?float
    {
        if ($number === null) return null;
        // 3 kind of thousands separators are found here too
        return floatval(str_replace(["’", " ", ","], "", $number));
    }
}