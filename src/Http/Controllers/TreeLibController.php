<?php

namespace RecursiveTree\Seat\TreeLib\Http\Controllers;

use Exception;
use RecursiveTree\Seat\TreeLib\Helpers\GiveawayHelper;
use Seat\Web\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;


class TreeLibController extends Controller
{

    public function enterGiveAway(){
        Gate::authorize('treelib-enter-giveaway');

        $character = auth()->user()->main_character_id;

        if(!$character){
            return redirect()->back()->with("error","No main character found!");
        }

        try {
            //enter giveaway
            GiveawayHelper::enterGiveaway($character);
            //if we entered successfully, update the entry date
            setting([GiveawayHelper::$GIVEAWAY_USER_STATUS,now()]);
        } catch (Exception $e){
            Log::error($e);
            return redirect()->back()->with("error","Could not enter giveaway! Please try later");
        }

        return redirect()->back()->with("success","Successfully entered giveaway");
    }
}