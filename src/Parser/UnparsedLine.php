<?php

namespace RecursiveTree\Seat\TreeLib\Parser;

use RecursiveTree\Seat\TreeLib\Helpers\DynamicProperties;

class UnparsedLine implements \JsonSerializable
{
    use DynamicProperties;

    public string $line;

    /**
     * @param string $line
     */
    public function __construct(string $line, array $properties)
    {
        $this->line = $line;
        $this->setProperties($properties);
    }

    /**
     * Specify data which should be serialized to JSON
     * @link https://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by <b>json_encode</b>,
     * which is a value of any type other than a resource.
     * @since 5.4
     */
    public function jsonSerialize(): mixed
    {
        return array_merge($this->getProperties(), [
            "line" => $this->line,
        ]);
    }
}