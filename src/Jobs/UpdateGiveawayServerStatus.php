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
        $server = TreeLibSettings::$GIVEAWAY_SERVER_URL->get("https://seat-giveaway.azurewebsites.net");

        $client = new Client([
            'timeout'  => 10.0,
        ]);

        $server_ok = true;

        try {
            $res = $client->request('GET', "$server/status",['http_errors' => false]);

            $server_ok = $this->parseStatusResponse($res);
        } catch (Exception $e){
            $server_ok = false;
            Log::error($e);
        }

        if(!$server_ok) {
            Log::info("seat-treelib: giveaway server is down!");
        }

        Cache::put(GiveawayHelper::$GIVEAWAY_SERVER_STATUS_CACHE_KEY, $server_ok);
    }

    private function parseStatusResponse($response): bool
    {
        $status = $response->getStatusCode();
        if ($status !== 200) {
            return false;
        }

        $content = $response->getBody();
        if(!$content){
            return false;
        }

        $data = json_decode($content);


        if(!isset($data->status)||!isset($data->reset_cycle)) {
            return false;
        }

        if ($data->status!=="ok"){
            return false;
        }

        if (!is_int($data->reset_cycle)){
            return false;
        }

        TreeLibSettings::$GIVEAWAY_RESET_CYCLE->set($data->reset_cycle);

        return true;
    }
}