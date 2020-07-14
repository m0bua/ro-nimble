<?php


namespace App\ValueObjects;


use App\Helpers\ConvertString;
use Exception;

class Property
{
    /**
     * @var string
     */
    private $property;

    /**
     * @var object
     */
    private $object;

    /**
     * Property constructor.
     * @param object $object
     * @param string $property
     * @throws Exception
     */
    public function __construct(object $object, string $property)
    {
        $this->object = $object;
        $this->property = ConvertString::camelCaseToSnake($property);

        if (!property_exists($this->object, $this->property)) {
            throw new Exception(sprintf('Property %s does not exists in %s', $this->property, get_class($this->object)));
        }
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->property;
    }

    /**
     * @return mixed|null
     */
    public function getValue()
    {
        if (property_exists($this->object, $this->property)) {
            return $this->object->property;
        }

        return null;
    }
}
