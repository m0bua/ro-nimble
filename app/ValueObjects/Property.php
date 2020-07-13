<?php


namespace App\ValueObjects;


use App\Helpers\ConvertString;

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
     */
    public function __construct(object $object, string $property)
    {
        $this->object = $object;
        $this->property = $property;

        try {
            if (!property_exists($this->object, $this->property)) {
                throw new \Exception(sprintf('Property %s does not exists in %s', $this->property, get_class($this->object)));
            }
        } catch (\Throwable $t) {
            report($t);
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
        $property = ConvertString::camelCaseToSnake($this->property);

        if (property_exists($this->object, $property)) {
            return $this->object->property;
        }

        return null;
    }
}
