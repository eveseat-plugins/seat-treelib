<?php

namespace RecursiveTree\Seat\TreeLib\Prices;

use RecursiveTree\Seat\TreeLib\Helpers\ItemList;
use RecursiveTree\Seat\TreeLib\Helpers\SimpleItemWithPrice;

abstract class AbstractPriceProvider
{
    /**
    * @param ItemList $items
    * @param PriceProviderSettings $settings
     * @return SimpleItemWithPrice[]
    */
    public static abstract function getPrices($items,$settings);
}