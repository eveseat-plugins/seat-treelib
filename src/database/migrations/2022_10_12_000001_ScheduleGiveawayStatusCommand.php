<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use RecursiveTree\Seat\TreeLib\Helpers\ScheduleHelper;
use Seat\Services\Models\Schedule;

return new class extends Migration
{
    public function up()
    {
        \Seat\Services\Facades\DeferredMigration::schedule(function (){
            ScheduleHelper::scheduleCommand("treelib:giveaway:server:status","52 * * * *");
        });
    }

    public function down()
    {
        \Seat\Services\Facades\DeferredMigration::schedule(function (){
            ScheduleHelper::removeCommand("treelib:giveaway:server:status");
        });
    }
};

