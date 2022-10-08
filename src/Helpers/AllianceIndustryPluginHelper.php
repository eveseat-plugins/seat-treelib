<?php

namespace RecursiveTree\Seat\TreeLib\Helpers;


class AllianceIndustryPluginHelper
{
    /**
     * @return bool
     */
    public static function pluginIsAvailable(){
        return
            class_exists(self::$API);
    }

    public static $API = "RecursiveTree\Seat\AllianceIndustry\Api\AllianceIndustryApi";
}