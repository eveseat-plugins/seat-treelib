<?php

namespace RecursiveTree\Seat\TreeLib\Http\Controllers;

use RecursiveTree\Seat\TreeLib\TreeLibSettings;
use Seat\Web\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Gate;


class SettingsController extends Controller
{
    public function settings(){
        $user_giveaway_optout = TreeLibSettings::$GIVEAWAY_USER_OPTOUT->get(false);

        return view("treelib::settings",compact("user_giveaway_optout"));
    }
}