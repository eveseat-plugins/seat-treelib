<?php

Route::group([
    'namespace'  => 'RecursiveTree\Seat\TreeLib\Http\Controllers',
    'middleware' => ['web', 'auth'],
    'prefix' => 'treelib',
], function () {
    Route::post('/giveaway/enter', [
        'as'   => 'treelib.enterGiveaway',
        'uses' => 'GiveAwayController@enterGiveAway',
    ]);

    Route::post('/giveaway/optout', [
        'as'   => 'treelib.optOutGiveaway',
        'uses' => 'GiveAwayController@optOutGiveaway',
    ]);

    Route::post('/giveaway/optin', [
        'as'   => 'treelib.optInGiveaway',
        'uses' => 'GiveAwayController@optInGiveaway',
    ]);
});

Route::group([
    'namespace'  => 'RecursiveTree\Seat\TreeLib\Http\Controllers',
    'middleware' => ['web', 'auth'],
    'prefix' => 'profile',
], function () {
    Route::get('/treelib/settings/discord', [
        'as'   => 'treelib.discordSettings',
        'uses' => 'SettingsController@discordSettings',
    ]);
});