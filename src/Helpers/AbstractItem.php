<?php

namespace RecursiveTree\Seat\TreeLib\Helpers;

use Seat\Eveapi\Models\Sde\InvType;

abstract class AbstractItem
{
    abstract function getTypeId();
    abstract function getAmount();

    public function name(){
        $type_id = $this->getTypeId();
        $type = InvType::find($type_id);
        if($type!=null) {
            return $type->typeName;
        } else {
            return "unknown-item-$type_id";
        }
    }

    public function toJson(){
        return [
            "type_id" => $this->getTypeId(),
            "amount" => $this->getAmount(),
            "name" => $this->name()
        ];
    }
}