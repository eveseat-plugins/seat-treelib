<?php

namespace RecursiveTree\Seat\TreeLib\Helpers;

class FittingPluginHelper
{
    public static function pluginIsAvailable(){
        return
            class_exists(self::$FITTING_PLUGIN_FITTING_MODEL)
            && class_exists(self::$FITTING_PLUGIN_DOCTRINE_MODEL);
    }

    public static $FITTING_PLUGIN_FITTING_MODEL = "CryptaTech\Seat\Fitting\Models\Fitting";
    public static $FITTING_PLUGIN_DOCTRINE_MODEL = "CryptaTech\Seat\Fitting\Models\Doctrine";
}