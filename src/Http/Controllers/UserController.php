<?php

namespace RecursiveTree\Seat\TreeLib\Http\Controllers;

class UserController
{
    public function disableAdvertisement()
    {
        setting(['enable_creator_ads', false]);

        return redirect()->back()->with("success","We respect your choice. You will no longer receive advertisements for creator codes.");
    }
}