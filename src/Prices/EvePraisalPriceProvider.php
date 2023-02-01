<?php

namespace RecursiveTree\Seat\TreeLib\Prices;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use RecursiveTree\Seat\TreeLib\Helpers\SimpleItemWithPrice;


class EvePraisalPriceProvider extends AbstractPriceProvider
{
    public static function getPrices($items, $settings)
    {
        $evepraisal_request = [];

        foreach ($items->iterate() as $item){
            $evepraisal_request[] = [
                "type_id"=>$item->getTypeId(),
                "quantity"=>$item->getAmount()
            ];
        }

        //appraise on evepraisal
        try {
            $client = new Client([
                'timeout'  => 5.0,
            ]);
            $response = $client->request('POST', "https://evepraisal.com/appraisal/structured.json",[
                'json' => [
                    'market_name' => $settings->getPreferredMarketHub(),
                    'persist' => 'false',
                    'items'=>$evepraisal_request,
                ]
            ]);
            //decode request
            $data = json_decode( $response->getBody());
        } catch (GuzzleException $e){
            throw new Exception("Failed to load prices from evepraisal!");
        }

        return array_map(function ($item) use ($settings) {
            if($settings->getPreferredPriceType()==="sell"){
                $price = $item->prices->sell->min;
            } else {
                $price = $item->prices->buy->max;
            }

            return new SimpleItemWithPrice(
                $item->typeID,
                $item->quantity,
                $price
            );
        },$data->appraisal->items);
    }
}