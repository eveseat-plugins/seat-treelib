<?php

namespace RecursiveTree\Seat\TreeLib\Http\Controllers;

use RecursiveTree\Seat\TreeLib\Helpers\PrioritySystem;
use RecursiveTree\Seat\TreeLib\Parser\Parser;
use Seat\Web\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Gate;


class ParserTestController extends Controller
{
    public function testParser(Request $request){
        $request->validate([
            "text"=>"string|nullable"
        ]);

        if($request->text) {
            $result = Parser::parseItems($request->text);
            $result->_debug_text = $request->text;
            return response()->json($result)->setEncodingOptions(JSON_PRETTY_PRINT);
        }

        return view("treelib::parsers");
    }
}