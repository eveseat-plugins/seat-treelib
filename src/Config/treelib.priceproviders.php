<?php
return [
    \RecursiveTree\Seat\TreeLib\Prices\EvePraisalPriceProvider::class => [
        'class'=>\RecursiveTree\Seat\TreeLib\Prices\EvePraisalPriceProvider::class,
        'name'=>'EvePraisal Prices'
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