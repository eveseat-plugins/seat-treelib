<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemovePriceCommand extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \RecursiveTree\Seat\TreeLib\Helpers\ScheduleHelper::removeCommand("treelib:esi:prices:update");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //I really don't need to support cross version upgrades
    }
}
