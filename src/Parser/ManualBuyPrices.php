<?php

namespace RecursiveTree\Seat\TreeLib\Parser;

use RecursiveTree\Seat\TreeLib\Items\EveItem;
use Seat\Eveapi\Models\Sde\InvType;

class ManualBuyPrices extends Parser
{
    protected static function parse($text)
    {
        $expr = implode("", [
            "^(?<name>.+?)",                                                //item name
            " x?(?<amount>".self::BIG_NUMBER_REGEXP.")",                    //amount
            "(?: (?<price>".self::BIG_NUMBER_REGEXP.")(?: ?ISK)?)?",        //price
            "$"                                                             //end
        ]);

        $lines = self::matchLines($expr, $text);

        //check if there are any matches
        if($lines->where("match","!=",null)->isEmpty()) return null;

        $items = [];
        $warning = false;

        foreach ($lines as $line){
            if($line->match === null) {
                $warning = true;
                continue;
            }

            $inv_model = InvType::where('typeName', $line->match->name)->first();

            if($inv_model==null){
                $warning = true;
                continue;
            }

            $item = new EveItem($inv_model);
            $item->amount = self::parseBigNumber($line->match->amount) ?? 1;
            $item->price = self::parseBigNumber($line->match->price);
            array_push($items,$item);
        }

        //if there are no items, ignore
        if(count($items)<1) return null;

        $result = new ParseResult(collect($items));
        $result->warning = $warning;
        return $result;
    }
}