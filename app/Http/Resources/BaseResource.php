<?php


namespace App\Http\Resources;


use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

abstract class BaseResource extends JsonResource
{
    /**
     * Returns list of fields for resource
     *
     * @example
     * 'nestedResourceField1' => [
     *      resource' => [
     *          'class' => NestedResource1::class,
     *          'method' => 'collection'
     *      ],
     * ],
     * 'nestedResourceField2' => [
     *      resource' => [
     *          'class' => NestedResource2::class,
     *          'method' => 'make'
     *      ],
     *      'alias' => 'nrf2',
     * ],
     * 'field1',
     * 'field2',
     *
     * @return array
     */
    abstract public function getResourceFields(): array;

    /**
     * @param Request $request
     * @return array
     * @throws Exception
     */
    public function toArray($request): array
    {
        $response = [];
        foreach ($this->getResourceFields() as $key => $field) {
            if (is_array($field)) {
                $fieldName = $field['alias'] ?? $key;
                $callable = $this->resolveResource($field);

                $response[$fieldName] = is_callable($callable)
                    ? $callable($this->resource[$fieldName])
                    : $this->resolveField($fieldName);
            } else {
                $response[$field] = $this->resolveField($field);
            }
        }

        return $response;
    }

    /**
     * @param array $field
     * @return array|string[] Array with class name and method
     * @throws Exception
     */
    private function resolveResource(array $field): array
    {
        if (!isset($field['resource'])) {
            return [];
        } elseif (!is_array($field['resource'])) {
            throw new Exception('Field "resource" must be of type array');
        }

        $class = $field['resource']['class'] ?? null;
        $method = $field['resource']['method'] ?? null;

        if (!isset($class)) {
            throw new Exception('Resource must have a "class" parameter');
        } elseif (!class_exists($class)) {
            throw new Exception("Class '$class' does not exist");
        }

        if (!isset($method)) {
            throw new Exception('Resource must have a "method" parameter');
        } elseif (!method_exists($class, $method)) {
            throw new Exception("Method '$method' does not exist in class '$class");
        }

        return [$class, $method];
    }

    private function resolveField(string $fieldName)
    {
        return $this->resource[$fieldName] ?? null;
    }
}
