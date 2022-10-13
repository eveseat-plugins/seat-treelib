<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use RecursiveTree\Seat\TreeLib\Helpers\ScheduleHelper;
use Seat\Services\Models\Schedule;

class ScheduleGiveawayStatusCommand extends Migration
{
    public function up()
    {
        ScheduleHelper::scheduleCommand("treelib:giveaway:server:status","52 * * * *");
    }

    public function down()
    {
        ScheduleHelper::removeCommand("treelib:giveaway:server:status");
    }
}

