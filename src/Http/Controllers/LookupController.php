<?php

namespace RecursiveTree\Seat\TreeLib\Http\Controllers;

use RecursiveTree\Seat\TreeLib\Helpers\PrioritySystem;
use Seat\Web\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Gate;


class LookupController extends Controller
{
    public function priceProviders(Request $request){
        $providers = collect(config('treelib.priceproviders'))
            ->values()
            ->map(function ($provider) {
                return [
                    'id'   => $provider['class'],
                    'text' => $provider['name'],
                ];
            });

        //dd($providers,config('treelib.priceproviders'));

        return response()->json([
            'results' => $providers,
        ]);
    }
}