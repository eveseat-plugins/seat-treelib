<?php

namespace RecursiveTree\Seat\TreeLib\Parser;

use RecursiveTree\Seat\TreeLib\Items\EveItem;
use Seat\Eveapi\Models\Sde\InvType;

class ItemListParser extends Parser
{
    protected static function parse($text)
    {
        $matches = [];
        $status = preg_match_all("/^(?<names>[\w '-]+?)$/m", $text, $matches);
        if(!$status) return null;

        $names = $matches["names"];

        $items = [];

        for ($i=0;$i<count($names);$i++){
            $item_name = $names[$i];

            $inv_model = InvType::where('typeName', $item_name)->first();

            if($inv_model==null){
                continue;
            }

            $item = new EveItem($inv_model);
            $item->amount = 1;

            array_push($items,$item);
        }

        $result = new ParseResult(collect($items));
        return $result;
    }
}