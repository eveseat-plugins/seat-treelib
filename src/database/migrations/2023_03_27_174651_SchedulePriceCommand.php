<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class SchedulePriceCommand extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \RecursiveTree\Seat\TreeLib\Helpers\ScheduleHelper::scheduleCommand("treelib:esi:prices:update","2 13 * * *");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        \RecursiveTree\Seat\TreeLib\Helpers\ScheduleHelper::removeCommand("treelib:esi:prices:update");
    }
}
