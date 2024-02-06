<?php

namespace RecursiveTree\Seat\TreeLib\Parser;

use Seat\Eveapi\Models\Sde\InvType;

class ItemListParser extends Parser
{
    protected static function parse(string $text, string $EveItemClass): ?ParseResult
    {
        // include translation star
        $expr = "^(?<name>[^*]+)\*?$";

        $lines = self::matchLines($expr, $text);
        //check if there are any matches
        if($lines->whereNotNull("match")->isEmpty()) return null;

        $items = [];
        $unparsed = [];

        $warning = false;

        foreach ($lines as $line){
            if($line->match === null) {
                $warning = true;
                continue;
            }

            $inv_model = InvType::where('typeName', $line->match->name)->first();

            if($inv_model==null){
                $warning = true;
                $unparsed[] = new UnparsedLine($line->line,[
                    'name' => $line->match->name,
                    'amount' => 1
                ]);
                continue;
            }

            $item = new $EveItemClass($inv_model);
            $item->amount = 1;
            $items[] = $item;
        }

        //if there are no items, ignore
        if(count($items)<1) return null;

        $result = new ParseResult(collect($items), collect($unparsed));
        $result->warning = $warning;
        return $result;
    }
}