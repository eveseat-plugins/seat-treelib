<?php

namespace RecursiveTree\Seat\TreeLib\Prices;

use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use RecursiveTree\Seat\TreeLib\Helpers\SimpleItemWithPrice;
use RecursiveTree\Seat\TreeLib\TreeLibServiceProvider;
use RecursiveTree\Seat\TreeLib\TreeLibSettings;
use Seat\Services\Traits\VersionsManagementTrait;


class EvePraisalPriceProvider extends AbstractPriceProvider
{
    use VersionsManagementTrait;

    public static function getPrices($items, $settings)
    {
        //evepraisal doesn't allow empty requests
        if($items->count()<1){
            return [];
        }

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