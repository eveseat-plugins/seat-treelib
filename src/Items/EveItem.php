<?php

namespace RecursiveTree\Seat\TreeLib\Items;
use RecursiveTree\Seat\TreeLib\Helpers\DynamicProperties;
use Seat\Eveapi\Models\Sde\InvType;

class EveItem
{
    use DynamicProperties;

    public $typeModel;

    /**
     * @param InvType $typeModel
     */
    public function __construct($typeModel)
    {
        $this->typeModel = $typeModel;
    }

    public static function fromTypeID($type_id){
        return new EveItem(InvType::find($type_id));
    }
}