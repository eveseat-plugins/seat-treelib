<?php

namespace RecursiveTree\Seat\TreeLib\Helpers;

class ItemList
{
    private $items;

    /**
     * @param $items
     */
    public function __construct($items)
    {
        $this->items = $items;
    }

    public function simplify(){
        $map = [];

        foreach ($this->items as $item){
            if(!array_key_exists($item->getTypeId(),$map)){
                $map[$item->getTypeId()] = $item->getAmount();
            } else {
                $map[$item->getTypeId()] += $item->getAmount();
            }
        }

        $new_list = [];
        foreach ($map as $type=>$amount){
            $new_list[] = new SimpleItem($type,$amount);
        }

        return new ItemList($new_list);
    }

    public function iterate(){
        foreach ($this->items as $item){
            yield $item;
        }
    }

    public function count(){
        return count($this->items);
    }

}