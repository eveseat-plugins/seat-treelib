<?php

namespace RecursiveTree\Seat\TreeLib\Parser;

use RecursiveTree\Seat\TreeLib\Items\EveItem;
use Seat\Eveapi\Models\Sde\InvType;

class MultibuyParser extends Parser
{
    protected static function parse($text)
    {
        $matches = [];
        $status = preg_match_all("/^(?<names>[\w '-]+?)\s+x?(?<amounts>\d+)$/m", $text, $matches);
        if(!$status) return null;

        $names = $matches["names"];
        $amounts = $matches["amounts"];

        $items = [];

        for ($i=0;$i<count($names);$i++){
            $item_name = $names[$i];
            $amount = intval($amounts[$i]);

            $inv_model = InvType::where('typeName', $item_name)->first();

            if($inv_model==null){
                continue;
            }

            $item = new EveItem($inv_model);
            $item->amount = $amount;

            array_push($items,$item);
        }

        $result = new ParseResult(collect($items));
        return $result;
    }
}