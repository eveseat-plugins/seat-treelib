<?php

namespace RecursiveTree\Seat\TreeLib\AccessControl\AccessProviders;

abstract class AbstractAccessProvider
{
    public abstract static function hasAccess($provider_map_id);
}