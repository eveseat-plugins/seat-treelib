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

    Route::post('/giveaway/optout', [
        'as'   => 'treelib.optOutGiveaway',
        'uses' => 'TreeLibController@optOutGiveaway',
    ]);

    Route::post('/giveaway/optin', [
        'as'   => 'treelib.optInGiveaway',
        'uses' => 'TreeLibController@optInGiveaway',
    ]);
});