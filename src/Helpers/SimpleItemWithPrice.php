<?php

namespace RecursiveTree\Seat\TreeLib\Helpers;

class SimpleItemWithPrice extends SimpleItem
{
    private $price;

    /**
     * @param int $type_id
     * @param int $amount
     * @param float|int $price
     */
    public function __construct($type_id, $amount, $price)
    {
        parent::__construct($type_id, $amount);
        $this->price = $price;
    }

    /**
     * @return int
     */
    public function getUnitPrice()
    {
        return $this->price;
    }

    /**
     * @return float|int
     */
    public function getTotalPrice(){
        return $this->price * $this->getAmount();
    }
}