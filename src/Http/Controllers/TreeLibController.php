<?php

namespace RecursiveTree\Seat\TreeLib\Http\Controllers;

use Seat\Web\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class TreeLibController extends Controller
{

    public function test(){
        return view("treelib::test");
    }
}