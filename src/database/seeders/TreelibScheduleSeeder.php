<?php

namespace RecursiveTree\Seat\TreeLib\database\seeders;

class TreelibScheduleSeeder extends \Seat\Services\Seeding\AbstractScheduleSeeder
{

    /**
     * @inheritDoc
     */
    public function getSchedules(): array
    {
        return [];
    }

    /**
     * @inheritDoc
     */
    public function getDeprecatedSchedules(): array
    {
        return [
            "treelib:esi:prices:update",
            "treelib:giveaway:server:status"
        ];
    }
}