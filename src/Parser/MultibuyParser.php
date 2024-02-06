<?php

namespace RecursiveTree\Seat\TreeLib\Parser;

use Seat\Eveapi\Models\Sde\InvType;

class MultibuyParser extends Parser
{
    protected static function parse(string $text, string $EveItemClass): ?ParseResult
    {
        $expr = implode("", [
            "^(?<name>[^\t*]+)\*?",                             //item name, excluding translation star
            "\t(?<amount>".self::BIG_NUMBER_REGEXP.")?",    //amount
            "\t(?<unit_price>".self::BIG_NUMBER_REGEXP.")?",      //unit price
            "\t(?<total>".self::BIG_NUMBER_REGEXP.")$",     //total price
        ]);

        $lines = self::matchLines($expr, $text);
        //check if there are any matches
        if($lines->whereNotNull("match")->isEmpty()) return null;

        $items = [];
        $unparsed = [];
        $warning = false;
        $has_total = false;

        foreach ($lines as $line){
            if($line->match === null) {
                $warning = true;
                continue;
            }

            if($line->match->name === "Total:") {
                $has_total = true;
                break;
            };

            $inv_model = InvType::where('typeName', $line->match->name)->first();
            $amount =  self::parseBigNumber($line->match->amount) ?? 1;
            $ingamePrice = self::parseBigNumber($line->match->unit_price);

            if($inv_model==null){
                $warning = true;
                $unparsed[] = new UnparsedLine($line->line,[
                    'name' => $line->match->name,
                    'amount' => $amount,
                    'ingamePrice' => $ingamePrice
                ]);
                continue;
            }

            $item = new $EveItemClass($inv_model);
            $item->amount = $amount;
            $item->ingamePrice = $ingamePrice;
            $items[] = $item;
        }

        //if there is no Total: at the end, it is not compatible with this format
        if(!$has_total) return null;

        //if there are no items, ignore
        if(count($items)<1) return null;

        $result = new ParseResult(collect($items), collect($unparsed));
        $result->warning = $warning;
        return $result;
    }
}