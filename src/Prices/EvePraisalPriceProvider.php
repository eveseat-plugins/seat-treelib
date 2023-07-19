<?php

namespace RecursiveTree\Seat\TreeLib\Prices;

use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use RecursiveTree\Seat\TreeLib\TreeLibSettings;
use Seat\Services\Traits\VersionsManagementTrait;


class EvePraisalPriceProvider extends AbstractPriceProvider
{

    public static function getPrices($items, $settings)
    {
        // to keep compatibility until it's usage decreases, redirect to other price providers
        if($settings->getPreferredPriceType()==="sell"){
            return SellPricesPriceProvider::getPrices($items, $settings);
        } else {
            return BuyPricesPriceProvider::getPrices($items, $settings);
        }
    }
}