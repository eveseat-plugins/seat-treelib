<?php

namespace RecursiveTree\Seat\TreeLib\Jobs;

use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Cache;



class EnterGiveaway implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public static $GIVEAWAY_SERVER_STATUS_CACHE_KEY = "treelib_giveaway_server_status";
    public static $GIVEAWAY_SERVER_URL_SETTING = "treelib_giveaway_server_url";

    public static $GIVEAWAY_USER_STATUS = "treelib_giveaway_status";

    private $character_id;

    /**
     * @param $character_id
     */
    public function __construct($character_id)
    {
        $this->character_id = $character_id;
    }

    public function tags()
    {
        return ["seat-treelib", "giveaway"];
    }

    public function handle()
    {
        //check status cache
        if (Cache::get(self::$GIVEAWAY_SERVER_STATUS_CACHE_KEY,true)){
            $server = setting(self::$GIVEAWAY_SERVER_URL_SETTING,true) ?? "https://giveaway.terminus.life/";

            $client = new Client([
                'timeout'  => 5.0,
            ]);

            try {
                $res = $client->request('GET', "$server/status");

                $status = $res->getStatusCode();
                if ($status !== 200) {
                    Cache::put(self::$GIVEAWAY_SERVER_STATUS_CACHE_KEY, false, 60 * 60); //1h
                    $this->fail(new Exception("The giveaway server is not running."));
                }

                $res = $client->request('POST', "$server/enter", [
                    'json' => ['character_id' => $this->character_id]
                ]);

                $status = $res->getStatusCode();
                if ($status !== 200) {
                    $this->fail(new Exception("The giveaway server couldn't accept the entry"));
                }

            } catch (Exception $e){
                Cache::put(self::$GIVEAWAY_SERVER_STATUS_CACHE_KEY, false, 60); //1 minute
                $this->fail($e);
            }
        } else {
            $this->fail(new Exception("The giveaway server is not running."));
        }
    }
}