<?php

namespace RecursiveTree\Seat\TreeLib;

use Exception;
use RecursiveTree\Seat\TreeLib\database\seeders\TreelibScheduleSeeder;
use RecursiveTree\Seat\TreeLib\Items\EveItem;
use RecursiveTree\Seat\TreeLib\Items\ToEveItem;
use Seat\Services\AbstractSeatPlugin;

use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Collection;
use Seat\Services\Contracts\HasTypeID;
use Seat\Services\Contracts\HasTypeIDWithAmount;
use Seat\Services\Items\EveTypeWithAmount;

class TreeLibServiceProvider extends AbstractSeatPlugin
{
    public function boot()
    {
        if (!$this->app->routesAreCached()) {
            include __DIR__ . '/Http/routes.php';
        }

        $this->loadViewsFrom(__DIR__ . '/resources/views/', 'treelib');
        $this->loadMigrationsFrom(__DIR__ . '/database/migrations/');
        $this->loadTranslationsFrom(__DIR__ . '/resources/lang', 'treelib');

        Blade::directive('checked', function ($condition) {
            return "<?php if($condition){ echo \"checked=\\\"checked\\\"\"; } ?>";
        });
        Blade::directive('selected', function ($condition) {
            return "<?php if($condition){ echo \"selected=\\\"selected\\\"\"; } ?>";
        });

        $this->extendCollections();
        $this->registerSkins();
    }

    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/Config/treelib.sidebar.php', 'package.sidebar');
        $this->registerPermissions(__DIR__ . '/Config/treelib.permissions.php', 'treelib');
        $this->registerDatabaseSeeders(TreelibScheduleSeeder::class);
    }

    private function extendCollections()
    {
        Collection::macro('simplifyItems', function () {
            return $this
                ->filter(function ($item){
                    if($item instanceof HasTypeIDWithAmount){
                        $amount = $item->getAmount();
                    } else {
                        $amount = $item->amount;
                    }
                    return $amount > 0;
                })
                ->groupBy(function($item){
                    if($item instanceof HasTypeID){
                        return $item->getTypeID();
                    } else {
                        return $item->typeModel->typeID;
                    }
                })
                ->map(function ($item_list) {
                    $first = $item_list->first();
                    if($first instanceof EveItem) {
                        // legacy case
                        $first->amount = $item_list->sum(function ($item) {
                            return $item->amount;
                        });
                        return $first;
                    } else {
                        // new case with modern eve items
                        return new EveTypeWithAmount($first, $item_list->sum(function ($item){
                            return $item->getAmount();
                        }));
                    }
                });
        });

        //named to keep the old name
        Collection::macro('toMultibuy', function () {
            $multibuy = "";
            foreach ($this as $item) {
                if(is_subclass_of($item,ToEveItem::class)){
                    $item = $item->toEveItem();
                }

                $name = $item->typeModel->typeName;
                $quantity = $item->amount ?? 1;
                $multibuy .= "$name $quantity" . PHP_EOL;
            }
            return $multibuy;
        });
    }

    private function registerSkins():void
    {
        $this->publishes([__DIR__ . '/resources/skins' => public_path('web/css/skins')],["public","seat"]);
        $this->mergeConfigFrom(__DIR__ . '/Config/treelib.skins.php', 'web.skins');
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