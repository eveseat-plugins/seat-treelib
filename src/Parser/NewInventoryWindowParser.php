<?php

namespace RecursiveTree\Seat\TreeLib\Parser;

use Seat\Eveapi\Models\Sde\InvGroup;
use Seat\Eveapi\Models\Sde\InvType;

class NewInventoryWindowParser extends Parser
{
    private static function determineItemType($match, $volume, $groupID)
    {
        self::getGroupID($match, $groupID);

        $query = InvType::query();

        if ($groupID !== null) {
            $query = $query->where("groupID", $groupID);
        }

        if ($volume !== null) {
            $query = $query->where("volume", $volume);
        }

        $items = $query->limit(2)->get();
        if ($items->count() == 1) return $items->get(0);

        return null;
    }

    protected static function parse(string $text, string $EveItemClass): ?ParseResult
    {

        $expr = implode("", [
            "^(?<name>[^\t*]+)\*?",                                            //name
            "\t(?<amount>" . self::BIG_NUMBER_REGEXP . "?)",             //amount
            "(?:\t(?<group>\D[^\t]*))?",                                  //group
            "(?:\t(?<category>\D[^\t]*))?",                               //category
            "(?:\t(?<size>\D[^\t]*)?)?",                                   //size. seems to be empty
            "(?:\t(?<slot>\D[^\t]*)?)?",                                   //slot
            "(?:\t(?<volume>" . self::BIG_NUMBER_REGEXP . ") m3)?",         //volume
            "(?:\t(?<meta>" . self::BIG_NUMBER_REGEXP . ")?)?",              //meta level
            "(?:\t(?<tech>" . self::BIG_NUMBER_REGEXP . "|None))?",         //tech level
            "(?:\t(?:(?<price>" . self::BIG_NUMBER_REGEXP . ") ISK)?)?",           //volume level
            "$"                                                         //end
        ]);

        //dd($expr);

        $lines = self::matchLines($expr, $text);

        //check if there are any matches
        if ($lines->whereNotNull("match")->isEmpty()) return null;

        $warning = false;
        $items = [];
        $unparsed = [];

        foreach ($lines as $line) {

            //if the line doesn't match, continue
            if ($line->match === null) continue;

            $groupID = null;

            //get the type from the name
            $type_model_query = InvType::where("typeName", $line->match->name);
            //check if the group matches to detected items named like a item
            if ($line->match->group) {
                self::getGroupID($line->match, $groupID);

                if ($groupID) $type_model_query = $type_model_query->where("groupID", $groupID);
            }
            //TODO category check once model is in core
            //get the model
            $type_model = $type_model_query->first();

            //amount
            $amount = self::parseBigNumber($line->match->amount);
            if ($amount == null) $amount = 1;
            if ($amount < 1) $amount = 1;

            //if we can't find the type over the name or the amount is not specified, it is a named item.
            $is_named = $type_model === null || $line->match->amount === null;

            //volume
            $volume = self::parseBigNumber($line->match->volume);
            if ($volume !== null) $volume = $volume / $amount;

            $ingamePrice = self::parseBigNumber($line->match->price);

            //if we can't guess the type from the name
            if ($type_model === null) {
                $type_model = self::determineItemType($line->match, $volume, $groupID);
            }

            //if we still don't have the type, ignore it
            if ($type_model === null) {
                $warning = true;
                $unparsed[] = new UnparsedLine($line->line,[
                    'name' => $line->match->name,
                    'amount' => $amount,
                    'volume' => $volume,
                    'is_named' => $is_named,
                    'ingamePrice' => $ingamePrice
                ]);
                continue;
            }

            $item = new $EveItemClass($type_model);
            $item->amount = $amount;
            $item->volume = $volume;
            $item->ingamePrice = $ingamePrice;
            $item->is_named = $is_named;
            $items[] = $item;
        }

        if (count($items) < 1) return null;

        $result = new ParseResult(collect($items), collect($unparsed));
        $result->warning = $warning;
        return $result;
    }

    private static function getGroupID($match, &$groupID): void
    {
        if ($groupID === null) $groupID = InvGroup::where("groupName", $match->group)->first()->groupID ?? null;
    }
}