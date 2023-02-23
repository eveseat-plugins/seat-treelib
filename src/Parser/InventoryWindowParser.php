<?php

namespace RecursiveTree\Seat\TreeLib\Parser;

use RecursiveTree\Seat\TreeLib\Items\EveItem;
use Seat\Eveapi\Models\Sde\InvGroup;
use Seat\Eveapi\Models\Sde\InvType;

class InventoryWindowParser extends Parser
{
    protected static function parse($text)
    {
        $matches = [];
        $status = preg_match_all("/^(?<name>[\w *'-_]+?)\t(?<amount>\d+)?\t(?<group>[\w *'-_]+?)\t.*?\t.*?\t(?<volume>\d[\d’]+) m3\t(?<price>\d+(?:’\d+)*(?:\.\d\d)?) ISK$/mu", $text, $matches);
        if(!$status) return null;

        $names = $matches["name"];
        $amounts = $matches["amount"];
        $groups = $matches["group"];
        $volumes = $matches["volume"];
        $prices = $matches["price"];

        $items = [];

        for ($i=0;$i<count($names);$i++){
            $item_name = $names[$i];
            $amount = intval($amounts[$i]);
            $group = $groups[$i];
            $volume = intval(str_replace("’","", $volumes[$i]));
            $price = intval(str_replace("’","", $prices[$i]));

            $inv_model = InvType::where('typeName', $item_name)->first();

            $is_named = $inv_model==null || $amount==0;

            if($inv_model==null){
                //we might be able to determine it over the group+volume
                $groupID = InvGroup::where("groupName",$group)->pluck("groupID")->first();
                if($groupID==null) continue;

                $data = InvType::where("volume", $volume)
                    ->where("groupID",$groupID)
                    ->limit(2)
                    ->get();

                if($data->count() !== 1) continue;
                $inv_model = $data->get(0);
            }

            $item = new EveItem($inv_model);
            $item->amount = $amount>0?$amount:1;
            $item->volume = $volume;
            $item->ingamePrice = $price;
            $item->is_named = $is_named;

            array_push($items,$item);
        }

        $result = new ParseResult(collect($items));
        return $result;
    }
}