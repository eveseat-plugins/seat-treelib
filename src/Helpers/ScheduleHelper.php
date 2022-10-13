<?php

namespace RecursiveTree\Seat\TreeLib\Helpers;

use Seat\Services\Models\Schedule;

class ScheduleHelper
{
    public static function scheduleCommand($command, $crontab){
        $schedule = new Schedule();
        $schedule->command = $command;
        $schedule->expression = $crontab;
        $schedule->allow_overlap = false;
        $schedule->allow_maintenance = false;
        $schedule->save();
    }

    public static function removeCommand($command){
        $schedules = Schedule::where("command", $command)->get();
        foreach ($schedules as $schedule){
            $schedule->delete();
        }
    }
}