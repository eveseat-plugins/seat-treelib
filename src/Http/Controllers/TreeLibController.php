<?php

namespace RecursiveTree\Seat\TreeLib\Http\Controllers;

use RecursiveTree\Seat\TreeLib\Jobs\EnterGiveaway;
use Seat\Web\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Gate;


class TreeLibController extends Controller
{

    public function enterGiveAway(){
        Gate::authorize('treelib-enter-giveaway');

        $character = auth()->user()->main_character_id;

        if(!$character){
            return redirect()->back()->with("error","No main character found!");
        }

        EnterGiveaway::dispatch($character)->onQueue('default');

        setting([EnterGiveaway::$GIVEAWAY_USER_STATUS,now()]);

        return redirect()->back()->with("success","Successfully entered giveaway");
    }
}