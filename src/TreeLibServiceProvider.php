<?php

namespace RecursiveTree\Seat\TreeLib;

use Seat\Services\AbstractSeatPlugin;

use  Seat\Eveapi\Jobs\Status\Status;

class TreeLibServiceProvider extends AbstractSeatPlugin
{
    public function boot(){
        if (!$this->app->routesAreCached()) {
            include __DIR__ . '/Http/routes.php';
        }

        $this->loadViewsFrom(__DIR__ . '/resources/views/', 'treelib');
        $this->loadMigrationsFrom(__DIR__ . '/database/migrations/');

        $this->publishes([
            __DIR__ . '/resources/js' => public_path('treelib/js')
        ]);
    }

    public function register(){
        $this->mergeConfigFrom(__DIR__ . '/Config/treelib.sidebar.php','package.sidebar');
        $this->registerPermissions(__DIR__ . '/Config/treelib.permissions.php', 'treelib');
    }

    public function getName(): string
    {
        return "Tree's SeAT Utility Library";
    }

    public function getPackageRepositoryUrl(): string
    {
        return 'https://www.youtube.com/watch?v=dQw4w9WgXcQ';
    }

    public function getPackagistPackageName(): string
    {
        return 'seat-treelib';
    }

    public function getPackagistVendorName(): string
    {
        return 'recursivetree';
    }
}