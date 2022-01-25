<?php

Route::group([
    'namespace'  => 'RecursiveTree\Seat\TreeLib\Http\Controllers',
    'middleware' => ['web', 'auth'],
    'prefix' => 'treelib',
], function () {
    Route::get('/test', [
        'as'   => 'treelib.test',
        'uses' => 'TreeLibController@test',
    ]);
});