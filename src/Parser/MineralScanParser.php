<?php

namespace RecursiveTree\Seat\TreeLib\Parser;

use Seat\Eveapi\Models\Sde\InvType;

/**
 * Used to parse mineral scans
 */
class MineralScanParser extends Parser
{

    protected static function parse(string $text, string $EveItemClass): ?ParseResult
    {
        $expr = implode("", [
            "^(?<name>[^\t*]+)\*?",           // Name
            "\t(?<amount>" . self::BIG_NUMBER_REGEXP . "?)", // amount
            "(?:\t(?<volume>" . self::BIG_NUMBER_REGEXP . ")\sm.)?",         //volume
            "(?:\t(?<distance>" . self::BIG_NUMBER_REGEXP . "))?\s(?<dist_type>k?m)", // distance
            "$"
        ]);

        $lines = self::matchLines($expr, $text);

        if ($lines->whereNotNull("match")->isEmpty()) return null;

        $warning = false;
        $parsed = [];
        $unparsed = [];

        foreach ($lines as $line) {
            if ($line->match === null) {
                continue;
            }

            $inv_model = InvType::where("typeName", $line->match->name)->first();

            $amount = self::parseBigNumber($line->match->amount);
            if ($amount == null) $amount = 1;
            if ($amount < 1) $amount = 1;

            $volume = self::parseBigNumber($line->match->volume);
            if ($volume !== null) $volume = $volume / $amount;

            $distance = self::parseBigNumber($line->match->distance);

            if ($line->match->dist_type === 'km') {
                $distance = $distance * 1000;
            }

            if ($inv_model == null) {
                $warning = true;
                $unparsed[] = new UnparsedLine($line->line,[
                    'name' => $line->match->name,
                    'amount' => $amount,
                    'volume' => $volume,
                    'distance' => $distance
                ]);
                continue;
            }

            $item = new $EveItemClass($inv_model);
            $item->amount = $amount;
            $item->volume = $volume;
            $item->distance = $distance;

            $parsed[] = $item;
        }

        if (count($parsed) < 1 && count($unparsed) < 1) return null;

        $result = new ParseResult(collect($parsed), collect($unparsed));
        $result->warning = $warning;
        return $result;
    }
}