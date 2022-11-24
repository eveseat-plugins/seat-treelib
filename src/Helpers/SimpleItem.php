<?php

namespace RecursiveTree\Seat\TreeLib\Helpers;

class SimpleItem extends AbstractItem
{
    private $amount;
    private $type_id;

    /**
     * @param $amount
     * @param $type_id
     */
    public function __construct($type_id, $amount)
    {
        $this->amount = $amount;
        $this->type_id = $type_id;
    }

    public function getTypeId()
    {
        return $this->type_id;
    }

    public function getAmount()
    {
        return $this->amount;
    }
}