<?php

namespace RecursiveTree\Seat\TreeLib\Prices;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Seat\Eveapi\Models\Market\Price;


class SellPricesPriceProvider extends AbstractPriceProvider
{

    public static function getPrices($items, $settings)
    {
        return $items->map(function ($item){
            $price = Price::find($item->typeModel->typeID)->sell_price ?? 0;
            if($item->price == null) {
                $item->price = $price;
            }
            $item->marketPrice = $price;
            return $item;
        });
    }
}