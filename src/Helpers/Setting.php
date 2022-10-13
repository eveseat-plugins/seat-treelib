<?php

namespace RecursiveTree\Seat\TreeLib\Helpers;

class Setting
{
    const PLUGIN_SETTING_PREFIX = "recursivetree";

    private $key;
    private $global;

    /**
     * @param $module
     * @param $setting
     */
    private function __construct($key, $global)
    {
        $this->global = $global;
        $this->key = $key;
    }

    public static function create($module, $setting,$global){
        return new Setting(sprintf("%s.%s.%s",self::PLUGIN_SETTING_PREFIX, $module,$setting),$global);
    }

    public static function createFromKey($key,$global){
        return new Setting($key,$global);
    }

    public function get($default=null){
        $value = setting($this->key,$this->global);

        if($value === null){
            return $default;
        }

        return $value;
    }

    public function set($value){
        setting([$this->key, $value], $this->global);
    }
}