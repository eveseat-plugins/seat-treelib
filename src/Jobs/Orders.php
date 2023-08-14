<?php

namespace RecursiveTree\Seat\TreeLib\Jobs;

use Illuminate\Support\Facades\DB;
use RecursiveTree\Seat\TreeLib\Models\MarketOrder;
use Seat\Eveapi\Jobs\EsiBase;

/**
 * Class Orders.
 *
 * @package Seat\Eveapi\Jobs\Market
 */
class Orders extends EsiBase
{

    /**
     * @var string
     */
    protected $method = 'get';

    /**
     * @var string
     */
    protected $endpoint = '/markets/{region_id}/orders/';

    /**
     * @var string
     */
    protected $version = 'v1';

    /**
     * @var array
     */
    protected $tags = ['public', 'market'];

    /**
     * Execute the job.
     *
     * @throws \Throwable
     */
    public function handle()
    {
        $job_start_time = now();

        // the region_id cached to speed up execution of the loop
        $region_id = setting('market_prices_region_id', true) ?: \Seat\Eveapi\Jobs\Market\History::THE_FORGE;

        //load all market data
        while (true) {
            //retrieve one page of market orders
            $orders = $this->retrieve(['region_id' => $region_id]);

            // map the ESI format to the database format
            // if the batch size is increased to 1000, it crashed
            collect($orders)->chunk(100)->each(function ($chunk) {
                // map the ESI format to the database format
                $records = $chunk->map(function ($order) {
                    $issued = carbon($order->issued);

                    return [
                        'order_id' => $order->order_id,
                        'duration' => $order->duration,
                        'is_buy_order' => $order->is_buy_order,
                        'issued' => $issued,
                        'expiry' => $issued->addDays($order->duration),
                        'location_id' => $order->location_id,
                        'min_volume' => $order->min_volume,
                        'price' => $order->price,
                        'range' => $order->range,
                        'system_id' => $order->system_id,
                        'type_id' => $order->type_id,
                        'volume_remaining' => $order->volume_remain,
                        'volume_total' => $order->volume_total,
                    ];
                });

                // update data in the db
                MarketOrder::upsert($records->toArray(), [
                    'order_id',
                    'duration',
                    'is_buy_order',
                    'issued',
                    'location_id',
                    'min_volume',
                    'price',
                    'range',
                    'system_id',
                    'type_id',
                    'volume_remaining',
                    'volume_total',
                    'expiry',
                ]);
            });

            // if there are more pages with orders, continue loading them
            if (! $this->nextPage($orders->pages)) break;
        }

        // remove old orders
        // if they didn't get updated, we can remove them
        MarketOrder::where('updated_at', '<=', $job_start_time)->delete();
    }
}
