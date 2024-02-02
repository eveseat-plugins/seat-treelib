<?php

namespace RecursiveTree\Seat\TreeLib\Parser;

use Seat\Eveapi\Models\Sde\InvType;

class ManualBuyPrices extends Parser
{
    protected static function parse(string $text, string $EveItemClass): ?ParseResult
    {
        $expr = implode("", [
            "^(?<name>[^*]+?)\*?",                                          //item name, including translation star
            " x?(?<amount>".self::BIG_NUMBER_REGEXP.")",                    //amount
            "(?: (?<price>".self::BIG_NUMBER_REGEXP.")(?: ?ISK)?)?",        //price
            "$"                                                             //end
        ]);

        $lines = self::matchLines($expr, $text);

        //check if there are any matches
        if ($lines->whereNotNull("match")->isEmpty()) return null;

        $items = [];
        $unparsed = [];
        $warning = false;

        foreach ($lines as $line){
            if($line->match === null) {
                $warning = true;
                continue;
            }

            $inv_model = InvType::where('typeName', $line->match->name)->first();
            $amount = self::parseBigNumber($line->match->amount) ?? 1;
            $price = self::parseBigNumber($line->match->price);

            if($inv_model==null){
                $warning = true;
                $unparsed[] = [
                    'name' => $line->match->name,
                    'amount' => $amount,
                    'price' => $price
                ];
                continue;
            }

            $item = new $EveItemClass($inv_model);
            $item->amount = $amount;
            $item->price = $price;
            $items[] = $item;
        }

        //if there are no items, ignore
        if(count($items)<1) return null;

        $result = new ParseResult(collect($items), collect($unparsed));
        $result->warning = $warning;
        return $result;
    }
}