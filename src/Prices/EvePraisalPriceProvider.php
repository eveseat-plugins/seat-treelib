<?php

namespace RecursiveTree\Seat\TreeLib\Prices;

use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use RecursiveTree\Seat\TreeLib\TreeLibSettings;
use Seat\Services\Traits\VersionsManagementTrait;


class EvePraisalPriceProvider extends AbstractPriceProvider
{
    use VersionsManagementTrait;

    public static function getPrices($items, $settings)
    {
        //evepraisal doesn't allow empty requests
        if($items->isEmpty()){
            return collect();
        }

        $evepraisal_request = [];

        foreach ($items as $item){
            $evepraisal_request[] = [
                "type_id"=>$item->typeModel->typeID,
                "quantity"=>1
            ];
        }

        //appraise on evepraisal
        try {
            $client = new Client([
                'timeout'  => 5.0,
            ]);
            $contact = TreeLibSettings::$INSTANCE_CONTACT_MAIL->get("recursivetreemail@gmail.com");

            $response = $client->request('POST', "https://evepraisal.com/appraisal/structured.json",[
                'json' => [
                    'market_name' => $settings->getPreferredMarketHub(),
                    'persist' => 'no',
                    'items'=>$evepraisal_request,
                ],
                'headers' => [
                    'User-Agent'=>"seat:seat-treelib-plugin / 1.x admin/$contact"
                ]
            ]);
            //decode request
            $data = json_decode( $response->getBody());
        } catch (GuzzleException $e){
            throw new Exception("Failed to load prices from evepraisal! $e");
        }

        //to preserve additional data in the items objects, convert the evepraisal response into a map typeId->price
        $type_prices = [];
        foreach ($data->appraisal->items as $item){
            if($settings->getPreferredPriceType()==="sell"){
                $price = $item->prices->sell->min;
            } else {
                $price = $item->prices->buy->max;
            }
            $type_prices[$item->typeID] = $price;
        }

        return $items->map(function ($item) use ($type_prices){
            if($item->price == null) {
                $item->price = $type_prices[$item->typeModel->typeID];
            }
            $item->marketPrice = $type_prices[$item->typeModel->typeID];
            return $item;
        });
    }
}