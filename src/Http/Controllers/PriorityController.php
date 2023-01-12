<?php

namespace RecursiveTree\Seat\TreeLib\Http\Controllers;

use RecursiveTree\Seat\TreeLib\Helpers\PrioritySystem;
use Seat\Web\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Gate;


class PriorityController extends Controller
{
    public function priorities(){
        $priorities = PrioritySystem::getPriorityData();

        return response()->json($priorities);
    }
}