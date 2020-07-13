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
     * Property constructor.
     * @param object $object
     * @param string $methodName
     */
    public function __construct(object $object, string $methodName)
    {
        $this->object = $object;
        $this->methodName = $methodName;

        try {
            $this->prefixValidate();
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
     * @return Property
     */
    public function getProperty(): Property
    {
        $property = ConvertString::stringWithoutPrefix($this->methodName, $this->methodPrefix);

        return new Property($this->object, $property);
    }
}
