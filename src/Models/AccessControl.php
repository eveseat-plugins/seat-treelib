<?php

namespace RecursiveTree\Seat\TreeLib\Models;

use Illuminate\Database\Eloquent\Model;

class AccessControl extends Model
{
    public $timestamps = false;

    protected $table = 'recursive_tree_seat_treelib_access_control';

    public function provider_map_entries(){
        return $this->hasMany(AccessProviderMapEntry::class,"access_control_id","id");
    }

}