<?php
return [
    // this price provider doesn't exist anymore, but for compatibility, we have to keep it
    \RecursiveTree\Seat\TreeLib\Prices\EvePraisalPriceProvider::class => [
        'class'=>\RecursiveTree\Seat\TreeLib\Prices\EvePraisalPriceProvider::class,
        'name'=>'EvePraisal Prices are no longer available. Please select another price provider.'
    ],
    \RecursiveTree\Seat\TreeLib\Prices\CCPPricesPriceProvider::class => [
        'class'=>\RecursiveTree\Seat\TreeLib\Prices\CCPPricesPriceProvider::class,
        'name'=>'CCP Prices'
    ],
    \RecursiveTree\Seat\TreeLib\Prices\SellPricesPriceProvider::class => [
        'class'=>\RecursiveTree\Seat\TreeLib\Prices\SellPricesPriceProvider::class,
        'name'=>'Seat Sell Prices'
    ],
    \RecursiveTree\Seat\TreeLib\Prices\BuyPricesPriceProvider::class => [
        'class'=>\RecursiveTree\Seat\TreeLib\Prices\BuyPricesPriceProvider::class,
        'name'=>'Seat Buy Prices'
    ]
];