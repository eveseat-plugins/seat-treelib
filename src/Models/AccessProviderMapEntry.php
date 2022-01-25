<?php

namespace RecursiveTree\Seat\Inventory\Models;

use Illuminate\Database\Eloquent\Model;
use RecursiveTree\Seat\TreeLib\AccessControl\AccessControlHelper;

class AccessProviderMapEntry extends Model
{
    public $timestamps = false;

    protected $table = 'recursive_tree_seat_treelib_access_provider_map';

}