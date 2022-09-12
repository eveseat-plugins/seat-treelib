<?php

Route::group([
    'namespace'  => 'RecursiveTree\Seat\TreeLib\Http\Controllers',
    'middleware' => ['web', 'auth'],
    'prefix' => 'treelib',
], function () {
    Route::post('/giveaway/enter', [
        'as'   => 'treelib.enterGiveaway',
        'uses' => 'TreeLibController@enterGiveAway',
    ]);
});