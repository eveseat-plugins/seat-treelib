<?php

namespace RecursiveTree\Seat\TreeLib\Jobs;

use RecursiveTree\Seat\TreeLib\Models\MarketOrder;
use Seat\Eveapi\Jobs\AbstractJob;
use Seat\Eveapi\Models\Market\Price;

/**
 * Class OrderAggregates.
 *
 * @package Seat\Eveapi\Jobs\Market
 */
class OrderAggregates extends AbstractJob
{
    protected $tags = ['market', 'orders'];

    public function handle()
    {
        // the time only needs to be loaded once instead of every time in the loop
        $now = carbon();

        // update sell orders
        MarketOrder::selectRaw('type_id, MIN(price) as sell_price')
            ->groupBy('type_id')
            ->where('is_buy_order', false)
            ->chunk(1000, function ($types) use ($now) {
                $types = $types->map(function ($type) use ($now) {
                    return [
                        'type_id'=>$type->type_id,
                        'sell_price'=>$type->sell_price,
                        'created_at'     => $now,
                        'updated_at'     => $now,
                    ];
                });

                Price::upsert($types->toArray(),
                    [
                        'type_id',
                        'sell_price',
                    ]
                );
            });

        // update buy orders
        MarketOrder::selectRaw('type_id, MAX(price) as buy_price')
            ->groupBy('type_id')
            ->where('is_buy_order', true)
            ->chunk(1000, function ($types) use ($now) {
                $types = $types->map(function ($type) use ($now) {
                    return [
                        'type_id' => $type->type_id,
                        'buy_price' => $type->buy_price,
                        'created_at'     => $now,
                        'updated_at'     => $now,
                    ];
                });

                Price::upsert($types->toArray(),
                    [
                        'type_id',
                        'buy_price',
                        'updated_at',
                    ]
                );
            });
    }
}
