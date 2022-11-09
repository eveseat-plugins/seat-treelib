<?php

namespace RecursiveTree\Seat\TreeLib;

use RecursiveTree\Seat\TreeLib\Helpers\Setting;

class TreeLibSettings
{
    public static $GIVEAWAY_USER_OPTOUT;
    public static $GIVEAWAY_USER_ENTRY_DATE;
    public static $GIVEAWAY_SERVER_URL;

    public static function init(){
        self::$GIVEAWAY_USER_OPTOUT         = Setting::create("treelib","giveaway.user.optout",false);

        //these need manual keys, since they were created before the setting system
        self::$GIVEAWAY_USER_ENTRY_DATE     = Setting::createFromKey("treelib_giveaway_status",false);
        self::$GIVEAWAY_SERVER_URL          = Setting::createFromKey("treelib_giveaway_server_url",true);
    }
}