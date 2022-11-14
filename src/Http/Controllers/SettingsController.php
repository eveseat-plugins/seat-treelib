<?php

namespace RecursiveTree\Seat\TreeLib\Http\Controllers;

use Seat\Web\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Gate;


class SettingsController extends Controller
{
    public function discordSettings(){
        return view("treelib::discord");
    }
}