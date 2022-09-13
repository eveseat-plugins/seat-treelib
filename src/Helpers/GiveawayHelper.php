<?php

namespace RecursiveTree\Seat\TreeLib\Helpers;

use Exception;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Cache;



class GiveawayHelper
{
    public static $GIVEAWAY_SERVER_STATUS_CACHE_KEY = "treelib_giveaway_server_status";
    public static $GIVEAWAY_SERVER_URL_SETTING = "treelib_giveaway_server_url";
    public static $GIVEAWAY_USER_STATUS = "treelib_giveaway_status";


    /**
     * @throws Exception
     */
    public static function enterGiveaway($character_id)
    {
        //check status cache
        if (Cache::get(self::$GIVEAWAY_SERVER_STATUS_CACHE_KEY,true)){
            $server = setting(self::$GIVEAWAY_SERVER_URL_SETTING,true) ?? "https://giveaway.terminus.life";

            $client = new Client([
                'timeout'  => 5.0,
            ]);

            try {
                $res = $client->request('GET', "$server/status");

                $status = $res->getStatusCode();
                if ($status !== 200) {
                    Cache::put(self::$GIVEAWAY_SERVER_STATUS_CACHE_KEY, false, 60 * 60); //1h
                    throw new Exception("The giveaway server is not running.");
                }

                $res = $client->request('POST', "$server/enter", [
                    'json' => ['character_id' => $character_id]
                ]);

                $status = $res->getStatusCode();
                if ($status !== 200) {
                    throw new Exception("The giveaway server couldn't accept the entry");
                }

            } catch (Exception $e){
                Cache::put(self::$GIVEAWAY_SERVER_STATUS_CACHE_KEY, false, 60); //1 minute
                throw $e;
            }
        } else {
            throw new Exception("The giveaway server is not running.");
        }
    }
}