<?php

namespace RecursiveTree\Seat\TreeLib;

use RecursiveTree\Seat\TreeLib\Helpers\GiveawayHelper;
use RecursiveTree\Seat\TreeLib\Http\Composers\EditAccessControlComposer;
use Seat\Services\AbstractSeatPlugin;

use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Gate;
use Seat\Web\Models\User;

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

        Artisan::command('treelib:giveawayserver {server}', function ($server) {
            setting([GiveawayHelper::$GIVEAWAY_SERVER_URL_SETTING,$server],true);
        });

        View::composer('treelib::giveaway', function ($view) {
            $server_status = Cache::get(GiveawayHelper::$GIVEAWAY_SERVER_STATUS_CACHE_KEY,true) && Gate::allows('treelib-enter-giveaway');

            $view->with("giveaway_active",$server_status);
        });

        Gate::define('treelib-enter-giveaway', function (User $user) {
            $last_entered = setting(GiveawayHelper::$GIVEAWAY_USER_STATUS);
            if ($last_entered){
                $time = carbon($last_entered);
                if ($time->diffInDays(now())<28){
                    return false;
                }
            }
            return true;
        });
    }

    public function register(){

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