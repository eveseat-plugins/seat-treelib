<?php

namespace RecursiveTree\Seat\TreeLib\Helpers;

class PrioritySystem
{
    private static $PRIORITY_DATA = [
            0=>["name"=>"Very Low","style"=>"secondary"],
            1=>["name"=>"Low","style"=>"secondary"],
            2=>["name"=>"Normal","style"=>"success"],
            3=>["name"=>"Preferred","style"=>"primary"],
            4=>["name"=>"Important","style"=>"warning"],
            5=>["name"=>"Critical","style"=>"danger"]
        ];

    public static function getBadge($priority){
        $priority = self::$PRIORITY_DATA[$priority] ?? null;
        $name = $priority["name"] ?? trans('web::seat.unknown');
        $style = $priority["style"] ?? "warning";
        return "<span class='badge badge-$style'>$name</span>";
    }

    public static function getPriorityData(){
        return self::$PRIORITY_DATA;
    }
}