<?php

namespace App\Helpers;

use DeepCopy\Exception\PropertyException;

abstract class Immutable
{
    /**
     * @param $name
     * @param $value
     * @return null
     */
    public function __set($name, $value)
    {
        return null;
    }

    /**
     * @param $name
     * @throws PropertyException
     */
    public function __get($name)
    {
        throw new PropertyException("Property {$name} not found in " . get_class($this));
    }
}
