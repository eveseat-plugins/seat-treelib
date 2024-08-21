<?php

Route::group([
    'namespace'  => 'RecursiveTree\Seat\TreeLib\Http\Controllers',
    'middleware' => ['web', 'auth'],
    'prefix' => 'treelib',
], function () {

    Route::get('/lookup/priceproviders', [
        'as'   => 'treelib.priceProviderLookup',
        'uses' => 'LookupController@priceProviders',
    ]);

    Route::get('/lookup/priceproviders', [
        'as'   => 'treelib.priceProviderLookup',
        'uses' => 'LookupController@priceProviders',
    ]);

    Route::get('/advertisment/creatorcode/disable', [
        'as'   => 'treelib.disableCreatorCodeAdvertisment',
        'uses' => 'UserController@disableAdvertisement',
    ]);

    Route::match(['get', 'post'],'/debug/parsers',[
        'as'   => 'treelib.debugParsers',
        'uses' => 'ParserTestController@testParser',
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