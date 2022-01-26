<?php

namespace RecursiveTree\Seat\TreeLib\Http\Composers;

use Illuminate\View\View;
use RecursiveTree\Seat\TreeLib\Models\AccessControl;

class EditAccessControlComposer
{
    public function compose(View $view)
    {
        $view_data = $view->getData();

        $access_control_id = null;
        if(array_key_exists("access_control_id",$view_data)){
            $access_control_id = $view_data["access_control_id"];
        }

        $access_control = AccessControl::find($access_control_id);

        $access_providers = collect();

        if($access_control != null) {
            $access_providers = $access_control->provider_map_entries;
        }

        $d2 = $view->getData()["test"] + 1;
        $view
            ->with('access_providers', $access_providers);
    }
}