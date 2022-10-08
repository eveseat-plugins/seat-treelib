<?php

namespace RecursiveTree\Seat\TreeLib\Helpers;

class SeatInventoryPluginHelper
{
    public static function pluginIsAvailable(){
        return
            class_exists(self::$INVENTORY_ITEM_MODEL)
            && class_exists(self::$INVENTORY_SOURCE_MODEL)
            && class_exists(self::$LOCATION_MODEL)
            && class_exists(self::$STOCK_MODEL)
            && class_exists(self::$STOCK_ITEM_MODEL)
            && class_exists(self::$STOCK_GROUP_MODEL);
    }

    public static $INVENTORY_ITEM_MODEL = "RecursiveTree\Seat\Inventory\Models\InventoryItem";
    public static $INVENTORY_SOURCE_MODEL = "RecursiveTree\Seat\Inventory\Models\InventorySource";
    public static $LOCATION_MODEL = "RecursiveTree\Seat\Inventory\Models\Location";
    public static $STOCK_MODEL = "RecursiveTree\Seat\Inventory\Models\Stock";
    public static $STOCK_ITEM_MODEL = "RecursiveTree\Seat\Inventory\Models\StockItem";
    public static $STOCK_GROUP_MODEL = "RecursiveTree\Seat\Inventory\Models\StockCategory";
}