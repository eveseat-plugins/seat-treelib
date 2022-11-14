<?php

namespace RecursiveTree\Seat\TreeLib\Http\Controllers;

use Exception;
use RecursiveTree\Seat\TreeLib\Helpers\GiveawayHelper;
use RecursiveTree\Seat\TreeLib\TreeLibSettings;
use Seat\Web\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Gate;


class GiveAwayController extends Controller
{

    public function enterGiveAway(){
        if (!GiveawayHelper::canUserEnter()){
            return redirect()->back()->with("error", "You are not eligible to entert this giveaway.");
        }

        $user = auth()->user();

        $character = $user->main_character_id;

        if(!$character){
            if($user->admin){
                return redirect()->back()->with("error", "The admin user can't enter the giveaway. please try again in a regular account!");
            } else {
                return redirect()->back()->with("error", "No main character found!");
            }
        }

        try {
            //enter giveaway
            $message = GiveawayHelper::enterGiveaway($character);
            //if we entered successfully, update the entry date
            TreeLibSettings::$GIVEAWAY_USER_RESET_CYCLE->set(TreeLibSettings::$GIVEAWAY_RESET_CYCLE->get(0));

            return redirect()->back()->with("success",$message);
        } catch (Exception $e){
            dd($e);
            return redirect()->back()->with("error","Could not enter giveaway. Please try again later. If the issue persists, contact recursive_tree#6692");
        }
    }

    public function optOutGiveaway(Request $request){
        $request->validate([
            "state"=>"required|boolean"
        ]);

        TreeLibSettings::$GIVEAWAY_USER_OPTOUT->set(boolval($request->state));
        return redirect()->back()->with("success","Successfully opted out of all giveaways.");
    }
}