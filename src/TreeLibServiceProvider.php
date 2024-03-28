<?php

namespace RecursiveTree\Seat\TreeLib;

use Exception;
use RecursiveTree\Seat\TreeLib\database\seeders\TreelibScheduleSeeder;
use RecursiveTree\Seat\TreeLib\Items\ToEveItem;
use Seat\Services\AbstractSeatPlugin;

use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Collection;

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
                    return $item->amount > 0;
                })
                ->groupBy("typeModel.typeID")
                ->map(function ($item_list) {
                    $first = $item_list->first();
                    $first->amount = $item_list->sum(function ($item) {
                        return $item->amount;
                    });
                    return $first;
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