<?php

namespace RecursiveTree\Seat\TreeLib\Prices;

use RecursiveTree\Seat\TreeLib\Helpers\ItemList;

abstract class AbstractPriceProvider
{
    /**
    * @param ItemList $items
    * @param PriceProviderSettings $settings
    */
    public static abstract function getPrices($items,$settings);
}