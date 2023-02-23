<?php

namespace RecursiveTree\Seat\TreeLib\Parser;

use RecursiveTree\Seat\TreeLib\Items\EveItem;
use Seat\Eveapi\Models\Sde\InvType;

class MultibuyParserWithPrice extends Parser
{
    protected static function parse($text)
    {
        $matches = [];
        $status = preg_match_all("/^(?<names>[\w '-]+?)\s+x?(?<amounts>\d+)(?:\s+-)*(?:\s+(?<prices>\d+)(?:ISK)?)?$/m", $text, $matches);
        if(!$status) return null;

        $names = $matches["names"];
        $amounts = $matches["amounts"];
        $prices = $matches["prices"];

        $items = [];

        for ($i=0;$i<count($names);$i++){
            $item_name = $names[$i];
            $amount = intval($amounts[$i]);
            $price = intval($prices[$i]);

            $inv_model = InvType::where('typeName', $item_name)->first();

            if($inv_model==null){
                continue;
            }

            $item = new EveItem($inv_model);
            $item->amount = $amount;
            $item->price = $price;
            $item->manualPrice = $price;

            array_push($items,$item);
        }

        $result = new ParseResult(collect($items));
        return $result;
    }
}