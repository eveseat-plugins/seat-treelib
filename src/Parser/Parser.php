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
        // Try to parse a mineral scan if nothing else matched
        MineralScanParser::class,
        // Latest parser is the most aggressive
        ItemListParser::class,
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

        $result = new ParseResult(collect(), collect());
        $result->warning = true;
        $result->_debug_parser = null;
        return $result;
    }

    protected abstract static function parse(string $text, string $EveItemClass);

    /**
     * REGEXP for number
     *
     * Keep decimal separators in sync with DECIMAL_SEPARATORS
     * Keep thousands separators in sync with THOUSANDS_SEPARATOR
     */
    public const BIG_NUMBER_REGEXP = "(?:\d+(?:[’‘\s .,]\d\d\d)*(?:[.,]\d\d)?)";

    private const DECIMAL_SEPARATORS = ".,";
    private const THOUSANDS_SEPARATOR = "’‘ .,";

    /**
     * @throws Exception
     */
    protected static function match($expr, $text): ?PREGMatch
    {
        $result = preg_match("/$expr/u", $text, $match);
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

    public static function parseBigNumber($number): ?float
    {
        if ($number === null) return null;

        /*
         * problem: depending on language, seat uses . as thousands and , as decimal separator, or exactly the other way around
         * => we have to detect the format
         * Luckily, if it is a decimal, it always seems to be rounded to two digits, while a thousands separator will have three digits
         * If we thee three digits, treat it as a thousands. Otherwise, treat it as a decimal
         */

        $last = -1;
        foreach (mb_str_split(self::DECIMAL_SEPARATORS) as $char){
            $pos = strrpos($number, $char);
            if($pos > $last) {
                $last = $pos;
            }
        }
        $thousands_part = $number;
        $decimal_part = "0";
        if($last > 0){
            $last_segment_len = strlen($number) - $last - 1;
            if($last_segment_len !== 3) {
                $thousands_part = substr($number,0, $last);
                $decimal_part = substr($number,$last+1);
            }
        }

        return floatval(str_replace(mb_str_split(self::THOUSANDS_SEPARATOR), "", $thousands_part).".".$decimal_part);
    }
}