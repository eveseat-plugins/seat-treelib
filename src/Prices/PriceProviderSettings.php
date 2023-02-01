<?php

namespace RecursiveTree\Seat\TreeLib\Prices;

interface PriceProviderSettings
{
    /**
     * @return string the name of the system
     */
    public function getPreferredMarketHub();

    /**
     * @return string Either buy or sell
     */
    public function getPreferredPriceType();
}