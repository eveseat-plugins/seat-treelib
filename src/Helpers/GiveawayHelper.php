<?php

namespace RecursiveTree\Seat\TreeLib\Helpers;

use Exception;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Cache;
use RecursiveTree\Seat\TreeLib\Jobs\UpdateGiveawayServerStatus;
use RecursiveTree\Seat\TreeLib\TreeLibSettings;
use Illuminate\Support\Facades\Log;


class GiveawayHelper
{
    public static $GIVEAWAY_SERVER_STATUS_CACHE_KEY = "treelib_giveaway_server_status";

    /**
     * @throws Exception
     */
    public static function enterGiveaway($character_id)
    {
        //check status cache
        if (Cache::get(self::$GIVEAWAY_SERVER_STATUS_CACHE_KEY,true)){
            $server = TreeLibSettings::$GIVEAWAY_SERVER_URL->get("https://giveaway.terminus.life");

            $client = new Client([
                'timeout'  => 5.0,
            ]);

            try {
                $res = $client->request('POST', "$server/enter", [
                    'json' => ['character_id' => $character_id],
                    'http_errors' => false,
                ]);

                $status = $res->getStatusCode();

                if($status === 200) {
                    return "You successfully entered the giveaway. Rewards are usually given out at the beginning of a new month when CCP distributes new skins to EVE partners.";
                } else if ($status === 400) {
                    throw new Exception("The giveaway server couldn't accept the entry.");
                } else if(500<=$status && $status <600){
                    throw new Exception("The giveaway server has an error. (HTTP $status)");
                } else {
                    throw new Exception("Unknown error while communicating with the giveaway server (HTTP $status)");
                }

            } catch (Exception $e){
                // the request failed, log the failure and trigger a status check
                Log::error($e);
                UpdateGiveawayServerStatus::dispatch();
                throw $e;
            }
        } else {
            throw new Exception("The giveaway server is not unavailable.");
        }
    }

    public static function canUserEnter(){
        $opt_out = TreeLibSettings::$GIVEAWAY_USER_OPTOUT->get(false);

        if ($opt_out){
            return false;
        }

        $current_reset_cycle = TreeLibSettings::$GIVEAWAY_RESET_CYCLE->get();
        $user_reset_cycle = TreeLibSettings::$GIVEAWAY_USER_RESET_CYCLE->get();//no problem that it is null, null!==any int and null in $current_reset_cycle is handled

        //dd($current_reset_cycle);
        //just after the update, we might not have a reset cycle loaded
        if($current_reset_cycle===null) return false;

        return $current_reset_cycle !== $user_reset_cycle;
    }
}