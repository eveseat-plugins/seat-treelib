<?php

namespace RecursiveTree\Seat\TreeLib\Prices;

abstract class AbstractPriceProvider
{
    /**
    * @param Illuminate\Support\Collection $items
    * @param PriceProviderSettings $settings
     * @return Illuminate\Support\Collection
    */
    public static abstract function getPrices($items,$settings);
}