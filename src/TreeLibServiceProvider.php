<?php

namespace RecursiveTree\Seat\TreeLib;

use Exception;
use RecursiveTree\Seat\TreeLib\Helpers\GiveawayHelper;
use RecursiveTree\Seat\TreeLib\Http\Composers\EditAccessControlComposer;
use RecursiveTree\Seat\TreeLib\Jobs\UpdateGiveawayServerStatus;
use Seat\Services\AbstractSeatPlugin;

use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Collection;

class TreeLibServiceProvider extends AbstractSeatPlugin
{
    public function boot(){
        if (!$this->app->routesAreCached()) {
            include __DIR__ . '/Http/routes.php';
        }

        $this->loadViewsFrom(__DIR__ . '/resources/views/', 'treelib');
        $this->loadMigrationsFrom(__DIR__ . '/database/migrations/');
        $this->loadTranslationsFrom(__DIR__ . '/resources/lang', 'treelib');

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
                $reset_cycle = TreeLibSettings::$GIVEAWAY_RESET_CYCLE->get();

                if(Cache::get(GiveawayHelper::$GIVEAWAY_SERVER_STATUS_CACHE_KEY)){
                    $this->info("The giveaway server at $url is ready. The reset cycle is $reset_cycle.");
                } else {
                    $this->info("The giveaway server at $url is unavailable.");
                }
            } else {
                UpdateGiveawayServerStatus::dispatch()->onQueue('default');
                $this->info("Scheduled server status update!");
            }
        });

        Artisan::command('treelib:test {--sync}', function () {
            throw new Exception("asd".strval($this->option("sync")));
        });

        View::composer('treelib::giveaway', function ($view) {
            $server_status = Cache::get(GiveawayHelper::$GIVEAWAY_SERVER_STATUS_CACHE_KEY,true) && GiveawayHelper::canUserEnter();

            $view->with("giveaway_active",$server_status);
        });

        Blade::directive('checked', function($condition) {
            return "<?php if($condition){ echo \"checked=\\\"checked\\\"\"; } ?>";
        });
        Blade::directive('selected', function($condition) {
            return "<?php if($condition){ echo \"selected=\\\"selected\\\"\"; } ?>";
        });

        $this->extendCollections();
    }

    public function register(){
        $this->mergeConfigFrom(__DIR__ . '/Config/treelib.sidebar.php','package.sidebar');
        $this->registerPermissions(__DIR__ . '/Config/treelib.permissions.php', 'treelib');
        TreeLibSettings::init();
    }

    private function extendCollections(){
        Collection::macro('simplifyItems', function () {
            return $this->groupBy("typeModel.typeID")->map(function ($item_list){
                $first = $item_list->first();
                $first->amount = $item_list->sum("amount");
                return $first;
            });
        });

        //named to keep the old name
        Collection::macro('toMultibuy', function () {
            $multibuy = "";
            foreach ($this as $item){
                $name = $item->typeModel->typeName;
                $quantity = $item->amount ?? 1;
                $multibuy .= "$name $quantity" . PHP_EOL;
            }
            return $multibuy;
        });
    }

    public function getName(): string
    {
        return "Tree's SeAT Utility Library";
    }

    public function getPackageRepositoryUrl(): string
    {
        return 'https://github.com/recursivetree/seat-treelib';
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