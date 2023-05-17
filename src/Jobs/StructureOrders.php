<?php

namespace RecursiveTree\Seat\TreeLib\Jobs;

use Illuminate\Support\Facades\DB;
use RecursiveTree\Seat\TreeLib\Models\MarketOrder;
use Seat\Eseye\Exceptions\RequestFailedException;
use Seat\Eveapi\Jobs\AbstractAuthCharacterJob;
use Seat\Eveapi\Jobs\EsiBase;
use Seat\Eveapi\Models\RefreshToken;
use Seat\Eveapi\Models\Universe\UniverseStructure;

/**
 * Class Orders.
 *
 * @package Seat\Eveapi\Jobs\Market
 */
class StructureOrders extends AbstractAuthCharacterJob
{

    /**
     * @var string
     */
    protected $method = 'get';

    /**
     * @var string
     */
    protected $endpoint = '/markets/structures/{structure_id}/';

    /**
     * @var string
     */
    protected $version = 'v1';

    /**
     * @var string
     */
    protected $scope = 'esi-markets.structure_markets.v1';

    /**
     * @var array
     */
    protected $tags = ['market', 'structure'];

    /**
     * @var int The structure ID to which this job is related.
     */
    protected $structure_id;

    public function __construct(RefreshToken $token, UniverseStructure $structure)
    {
        $this->structure_id = $structure->structure_id;

        parent::__construct($token);
    }

    /**
     * Execute the job.
     *
     * @throws \Throwable
     */
    public function handle()
    {
        $structure_id = $this->getStructureId();
        $structure = UniverseStructure::find($structure_id);

        // Remove older orders for the structure
        MarketOrder::where('location_id', '=', $this->getStructureId())
            ->delete();

        //load all market data
        while (true) {
            //retrieve one page of market orders
            $orders = $this->retrieve(['structure_id' => $structure_id]);


            // map the ESI format to the database format
            // if the batch size is increased to 1000, it crashed
            collect($orders)->chunk(100)->each(function ($chunk) use ($structure) {
                // map the ESI format to the database format
                $records = $chunk->map(function ($order) use ($structure) {
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
                        'system_id' => $structure->solar_system_id,
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
                    'expiry'
                ]);

            });

            // if there are more pages with orders, continue loading them
            if (! $this->nextPage($orders->pages)) break;
        }
    }

    /**
     * Get the structure ID to which this job is related.
     *
     * @return int
     */
    public function getStructureId(): int
    {
        return $this->structure_id;
    }
}
