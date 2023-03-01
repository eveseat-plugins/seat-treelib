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

    Route::get('/lookup/priceproviders', [
        'as'   => 'treelib.priceProviderLookup',
        'uses' => 'LookupController@priceProviders',
    ]);
});

Route::group([
    'namespace'  => 'RecursiveTree\Seat\TreeLib\Http\Controllers',
    'middleware' => ['web', 'auth'],
    'prefix' => 'profile',
], function () {
    Route::get('/treelib/settings', [
        'as'   => 'treelib.settings',
        'uses' => 'SettingsController@settings',
    ]);
});

Route::group([
    'namespace'  => 'RecursiveTree\Seat\TreeLib\Http\Controllers',
    'middleware' => ['web', 'auth'],
    'prefix' => 'priority',
], function () {
    Route::get('/list', [
        'as'   => 'treelib.prioritiesList',
        'uses' => 'PriorityController@priorities',
    ]);
});