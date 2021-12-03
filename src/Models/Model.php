<?php

namespace ShopModule\WeclappApi\Models;

use stdClass;

abstract class Model
{

    function __call($name, $arguments)
    {
        // skip existing functions
        if ( ! function_exists($this, $name)) {
            if (preg_match('#^get([A-Z][A-Za-z]*)#', $name, $matches)) {
                $property = lcfirst($matches[1]);
                if (property_exists($this, $property)) {
                    return $this->$property;
                }
            }
        }
    }

    public function getCustomAttributes(): array
    {
        return $this->customAttributes ?? [];
    }

    public function getCustomAttribute(int $attributeDefinitionId): ?stdClass
    {
        foreach ($this->getCustomAttributes() as $attribute) {
            if ($attributeDefinitionId == $attribute->attributeDefinitionId) {
                return $attribute->numberValue;
            }
        }
        return null;
    }

}