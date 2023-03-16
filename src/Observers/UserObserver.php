<?php

namespace RecursiveTree\Seat\TreeLib\Observers;

use Seat\Services\Settings\Profile;
use Illuminate\Support\Facades\Cache;

class UserObserver
{
    public function retrieved($user){
        $now = now();
        //check for the data
        if($now->month===4 && $now->day===1) {
            //I guess you found it. congrats
            // add skin to skin list
            array_push(Profile::$options["skins"], "hellokitty");
            // temporarily overwrite settings using the cache
            Cache::put(Profile::get_key_prefix("skin", $user->id), "hellokitty", 2);
        }
    }
}