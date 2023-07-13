<?php

namespace RecursiveTree\Seat\TreeLib\Items;
use JsonSerializable;
use RecursiveTree\Seat\TreeLib\Helpers\DynamicProperties;
use Seat\Eveapi\Models\Sde\InvType;

class EveItem implements JsonSerializable, ToEveItem
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

    public static function fromTypeID($type_id, $data=[]){
        $item = new EveItem(InvType::find($type_id));
        $item->setProperties($data);
        return $item;
    }

    public function jsonSerialize()
    {
        return array_merge($this->getProperties(),[
           "typeID"=>$this->typeModel->typeID,
           "name"=> $this->typeModel->typeName,
        ]);
    }

    public function toEveItem(): EveItem
    {
        return $this;
    }
}