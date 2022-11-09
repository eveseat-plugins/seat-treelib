<?php

namespace RecursiveTree\Seat\TreeLib;

use RecursiveTree\Seat\TreeLib\Helpers\Setting;

class TreeLibSettings
{
    public static $GIVEAWAY_USER_OPTOUT;
    public static $GIVEAWAY_USER_ENTRY_DATE;
    public static $GIVEAWAY_SERVER_URL;
    public static $GIVEAWAY_RESET_CYCLE;
    public static $GIVEAWAY_USER_RESET_CYCLE;

    public static function init(){
        self::$GIVEAWAY_USER_OPTOUT         = Setting::create("treelib","giveaway.user.optout",false);
        self::$GIVEAWAY_USER_RESET_CYCLE         = Setting::create("treelib","giveaway.user.reset_cycle",true);
        self::$GIVEAWAY_RESET_CYCLE         = Setting::create("treelib","giveaway.server.reset_cycle",true);

        //these need manual key, since they were created before the setting system
        self::$GIVEAWAY_USER_ENTRY_DATE     = Setting::createFromKey("treelib_giveaway_status",false);
        self::$GIVEAWAY_SERVER_URL          = Setting::createFromKey("treelib_giveaway_server_url",true);
    }
}