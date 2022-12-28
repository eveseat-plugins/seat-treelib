<?php

namespace RecursiveTree\Seat\TreeLib\Helpers;

use Seat\Eveapi\Models\Sde\InvType;

abstract class AbstractItem
{
    abstract function getTypeId();
    abstract function getAmount();

    /**
     * @return string the item name
     */
    public function getName(){
        $type_id = $this->getTypeId();
        $type = InvType::find($type_id);
        if($type!=null) {
            return $type->typeName;
        } else {
            return "unknown-item-$type_id";
        }
    }

    /**
     * @deprecated
     * @return string the item name
     */
    public function name(){
        return $this->getName();
    }

    public function toJson(){
        return [
            "type_id" => $this->getTypeId(),
            "amount" => $this->getAmount(),
            "name" => $this->name()
        ];
    }
}