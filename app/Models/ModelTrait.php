<?php
namespace App\Models;

use App\Helpers\ConvertString;
use App\ValueObjects\Method;
use ReflectionClass;
use ReflectionProperty;

trait ModelTrait
{
    /**
     * @var array
     */
    protected $data;

    /**
     * @var array
     */
    protected $params;

    /**
     * @var array
     */
    protected $vars;

    /**
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param $data
     * @return $this
     * @throws \ReflectionException
     */
    public function load($data)
    {
        $reflection = new ReflectionClass($this);
        $this->vars = $reflection->getProperties(ReflectionProperty::IS_PRIVATE);
        $this->data = $data;

        foreach ($this->vars as $var) {
            $this->{ConvertString::getSetter($var->name)}();
        }

        return $this;
    }


    /**
     * @param array $params
     * @return array|callable
     */
    public function save()
    {
        foreach ($this->vars as $var) {
            $this->params[ConvertString::camelCaseToSnake($var->name)] = $this->{$var->name};
        }

        return $this->index([
            'id' => $this->params['id'],
            'body' => $this->params
        ]);
    }

    /**
     * @param string $name
     * @param array $arguments
     */
    public function __call(string $name, array $arguments)
    {
        $method = new Method($this, $name);

        switch ($method->getPrefix()) {
            case Method::GET:
                return $this->{$method->getPropertyName()};
                break;
            case Method::SET:
                $this->{$method->getPropertyName()} = $method->getPropertyValue();
                return $this;
                break;
        }
    }
}
