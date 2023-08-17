<?php

namespace RecursiveTree\Seat\TreeLib\Parser;

use Seat\Eveapi\Models\Sde\InvType;

class ItemListParser extends Parser
{
    protected static function parse(string $text, string $EveItemClass)
    {
        // include translation star
        $expr = "^(?<name>[^*]+)\*?$";

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

            $item = new $EveItemClass($inv_model);
            $item->amount = 1;
            array_push($items,$item);
        }

        //if there are no items, ignore
        if(count($items)<1) return null;

        $result = new ParseResult(collect($items));
        $result->warning = $warning;
        return $result;
    }
}