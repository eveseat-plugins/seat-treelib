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
        // this migration is a stub so that migrating downwards still works. The actual logic is in the schedule seeder
    }

    public function down()
    {
        // this migration is a stub so that migrating downwards still works. The actual logic is in the schedule seeder
    }
};

