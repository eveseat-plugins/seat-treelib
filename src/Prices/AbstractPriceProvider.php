<?php

namespace RecursiveTree\Seat\TreeLib\Prices;

use RecursiveTree\Seat\TreeLib\Helpers\ItemList;
use RecursiveTree\Seat\TreeLib\Helpers\SimpleItemWithPrice;
use RecursiveTree\Seat\TreeLib\Items\EveItem;

abstract class AbstractPriceProvider
{
    /**
    * @param Illuminate\Support\Collection $items
    * @param PriceProviderSettings $settings
     * @return Illuminate\Support\Collection
    */
    public static abstract function getPrices($items,$settings);
}