<?php

namespace RecursiveTree\Seat\TreeLib\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Notification;
use RecursiveTree\Seat\TreeLib\Helpers\GiveawayHelper;
use RecursiveTree\Seat\TreeLib\TreeLibSettings;
use Illuminate\Support\Facades\Log;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Cache;


class UpdateGiveawayServerStatus implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;


    public function tags()
    {
        return ["seat-treelib", "giveaway","status"];
    }

    public function handle()
    {
        $server = TreeLibSettings::$GIVEAWAY_SERVER_URL->get("https://giveaway.terminus.life");

        $client = new Client([
            'timeout'  => 10.0,
        ]);

        $server_up = true;

        try {
            $res = $client->request('GET', "$server/status",['http_errors' => false]);

            $status = $res->getStatusCode();
            if ($status !== 200) {
                $server_up = false;
            }
        } catch (Exception $e){
            $server_up = false;
            Log::error($e);
        }

        if(!$server_up) {
            Log::info("seat-treelib: giveaway server is down!");
        }

        Cache::put(GiveawayHelper::$GIVEAWAY_SERVER_STATUS_CACHE_KEY, $server_up);
    }
}