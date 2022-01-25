<?php

namespace RecursiveTree\Seat\TreeLib\AccessControl;

use RecursiveTree\Seat\Inventory\Models\AccessControl;
use RecursiveTree\Seat\Inventory\Models\AccessProviderMapEntry;

class AccessControlHelper
{
    public static function create($use_case)
    {
        $access_control = new AccessControl();
        $access_control->used_for = $use_case;
        return $access_control;
    }

    public static function update(){

    }

    public static function hasAccess($access_control){
        $entries = $access_control->provider_map_entries;

        if(auth()->user()->can("treelib.access_override")){
            return true;
        }

        foreach ($entries as $entry){
            if(!class_exists($entry->provider_class)) continue;
            if(!method_exists($entry->provider_class,"hasAccess")) continue;

            $allows_access = $entry->provider_class::hasAccess($entry->id);

            if($allows_access){
                return true;
            }
        }

        return false;
    }
}