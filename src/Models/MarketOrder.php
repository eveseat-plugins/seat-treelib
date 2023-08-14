<?php

namespace RecursiveTree\Seat\TreeLib\Models;

use Illuminate\Database\Eloquent\Model;
use Seat\Eveapi\Models\Sde\InvType;
use Seat\Eveapi\Models\Sde\SolarSystem;
use Seat\Eveapi\Models\Universe\UniverseStation;
use Seat\Eveapi\Models\Universe\UniverseStructure;
use Seat\Eveapi\Traits\CanUpsertIgnoreReplace;

/**
 * Class Price.
 *
 * @package Seat\Eveapi\Models\Market
 */
class MarketOrder extends Model
{

    use CanUpsertIgnoreReplace;

    /**
     * @var bool
     */
    protected static $unguarded = true;

    /**
     * @var bool
     */
    public $incrementing = false;

    /**
     * @var string
     */
    protected $primaryKey = 'order_id';

    /**
     * @var string
     */
    protected $table = 'market_orders';

    /**
     * @var bool
     */
    public $timestamps = true;

    /**
     * @var array
     */
    protected $dates = [
        'issued',
        'expiry',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function type()
    {
        return $this->hasOne(InvType::class, 'typeID', 'type_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function solar_system()
    {
        return $this->hasOne(SolarSystem::class, 'system_id', 'system_id')
            ->withDefault();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function structure()
    {
        return $this->hasOne(UniverseStructure::class, 'structure_id', 'location_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function station()
    {
        return $this->hasOne(UniverseStation::class, 'station_id', 'location_id');
    }
}
