<?php

use Illuminate\Database\Migrations\Migration;
use RecursiveTree\Seat\TreeLib\Helpers\ScheduleHelper;
use Seat\Services\Facades\DeferredMigration;

return new class extends Migration
{
    public function up()
    {
        DeferredMigration::schedule(function (){
            ScheduleHelper::removeCommand("treelib:giveaway:server:status");
        });
    }

    public function down()
    {
        DeferredMigration::schedule(function (){
            ScheduleHelper::scheduleCommand("treelib:giveaway:server:status","52 * * * *");
        });
    }
};

