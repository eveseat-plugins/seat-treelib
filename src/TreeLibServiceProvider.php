<?php

namespace RecursiveTree\Seat\TreeLib;

use RecursiveTree\Seat\TreeLib\Helpers\GiveawayHelper;
use RecursiveTree\Seat\TreeLib\Http\Composers\EditAccessControlComposer;
use RecursiveTree\Seat\TreeLib\Jobs\UpdateGiveawayServerStatus;
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

        Artisan::command('treelib:giveaway:server {server}', function ($server) {
            TreeLibSettings::$GIVEAWAY_SERVER_URL->set($server);
        });

        Artisan::command('treelib:giveaway:server:status {--sync}', function () {
            if ($this->option("sync")){
                $this->info("processing...");
                UpdateGiveawayServerStatus::dispatchNow();
                $this->info("Updated server status.");

                $url = TreeLibSettings::$GIVEAWAY_SERVER_URL->get();

                if(Cache::get(GiveawayHelper::$GIVEAWAY_SERVER_STATUS_CACHE_KEY)){
                    $this->info("The giveaway server at $url is ready.");
                } else {
                    $this->info("The giveaway server at $url is unavailable.");
                }
            } else {
                UpdateGiveawayServerStatus::dispatch()->onQueue('default');
                $this->info("Scheduled server status update!");
            }
        });

        View::composer('treelib::giveaway', function ($view) {
            $server_status = Cache::get(GiveawayHelper::$GIVEAWAY_SERVER_STATUS_CACHE_KEY,true) && GiveawayHelper::canUserEnter();

            $view->with("giveaway_active",$server_status);
        });
    }

    public function register(){
        TreeLibSettings::init();
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