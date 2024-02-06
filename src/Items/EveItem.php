<?php

namespace RecursiveTree\Seat\TreeLib\Items;
use JsonSerializable;
use RecursiveTree\Seat\TreeLib\Helpers\DynamicProperties;
use Seat\Eveapi\Models\Sde\InvType;
use Seat\Services\Contracts\HasTypeID;

class EveItem implements JsonSerializable, ToEveItem, HasTypeID
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
        $typeModel = InvType::find($type_id);
        if(!$typeModel){
            //TODO clean this up
            $typeModel = new InvType();
            $typeModel->typeID = $type_id;
            $typeModel->typeName = "Unknown Item";
            $typeModel->bypassReadOnly(true);
            $typeModel->save();
        }
        $item = new EveItem($typeModel);
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

    /**
     * @return int The eve type id of this object
     */
    public function getTypeID(): int
    {
        return $this->typeModel->typeID;
    }
}