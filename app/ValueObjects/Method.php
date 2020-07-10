<?php

namespace App\ValueObjects;

use App\Helpers\ConvertString;
use ReflectionClass;
use Exception;

class Method
{
    public const GET = 'get';
    public const SET = 'set';

    /**
     * @var object
     */
    private $object;

    /**
     * @var string
     */
    private $methodName;

    /**
     * @var string
     */
    private $methodPrefix;

    /**
     * @var string
     */
    private $propertyName;

    /**
     * Property constructor.
     * @param object $object
     * @param string $methodName
     */
    public function __construct(object $object, string $methodName)
    {
        $this->methodName = $methodName;
        $this->object = $object;

        try {
            $this->prefixValidate();
            $this->propertyValidate();
        } catch (\Throwable $t) {
            report($t);
        }
    }

    /**
     * Validate prefix of method
     *
     * @throws Exception
     */
    public function prefixValidate()
    {
        $reflection = new ReflectionClass($this);
        $prefix = ConvertString::getPrefix($this->methodName, $reflection->getConstants());

        if (!$prefix) {
            throw new \Exception(sprintf('Incorrect method %s', $this->methodName));
        }

        $this->methodPrefix = $prefix;
    }

    /**
     * @return string
     */
    public function getPrefix()
    {
        return $this->methodPrefix;
    }

    /**
     * Validate property of method
     * @throws Exception
     */
    public function propertyValidate()
    {
        $property = ConvertString::stringWithoutPrefix($this->methodName, $this->methodPrefix);

        if (!property_exists($this->object, $property)) {
            throw new Exception(sprintf('Property %s does not exists in %s', $this->methodName, get_class($this->object)));
        }

        $this->propertyName = $property;
    }

    /**
     * @return string
     */
    public function getPropertyName()
    {
        return $this->propertyName;
    }

    /**
     * @return string|null
     */
    public function getPropertyValue()
    {
        $property = ConvertString::camelCaseToSnake($this->propertyName);

        if (array_key_exists($property, $this->object->getData())) {
            return $this->object->getData()[$property];
        }

        return null;
    }
}
