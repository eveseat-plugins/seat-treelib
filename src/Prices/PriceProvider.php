<?php

namespace RecursiveTree\Seat\TreeLib\Prices;

interface PriceProvider
{
    /**
     * @param Illuminate\Support\Collection $items
     * @param PriceProviderSettings $settings
     * @return Illuminate\Support\Collection
     */
    public static function getPrices($items,$settings);
}