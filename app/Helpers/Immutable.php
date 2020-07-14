<?php

namespace App\Helpers;

use DeepCopy\Exception\PropertyException;

abstract class Immutable
{

    /**
     * @param $name
     * @param $value
     * @throws PropertyException
     */
    public function __set($name, $value)
    {
        throw new PropertyException("Property {$name} not found in " . get_class($this));
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
