<?php


namespace App\Http\Resources;


use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

abstract class BaseResource extends JsonResource
{
    const RESERVED_FIELD = 'field';
    const RESERVED_ALIAS = 'alias';
    const RESERVED_INSIDE = 'inside';
    const RESERVED_RESOURCE = 'resource';
    const RESERVED_CLASS = 'class';
    const RESERVED_METHOD = 'method';

    /**
     * Returns list of fields for resource
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
        return $this->resolveStruct($this->getResourceFields(), $this->resource);
    }

    /**
     * @param array $resourceFields
     * @param $resource
     * @return array
     * @throws Exception
     */
    private function resolveStruct(array $resourceFields, $resource): array
    {
        $response = [];
        foreach ($resourceFields as $field) {
            if (!is_array($field)) {
                $response[$field] = $this->resolveField($resource, $field);
                continue;
            }

            if (!isset($field[self::RESERVED_FIELD])) {
                throw new Exception("Parameter 'field' not found in resource: " . static::class);
            }

            $parameterName = $this->resolveParameterName($field);

            if ($this->leaveEmpty($resource, $field)) {
                $response[$parameterName] = null;
                continue;
            }

            if (isset($field[self::RESERVED_RESOURCE])) {
                $callable = $this->resolveResource($field[self::RESERVED_RESOURCE]);
                if (is_callable($callable)) {
                    $response[$parameterName] = $callable($this->resolveArrayField($resource, $field[self::RESERVED_FIELD]));
                }

                continue;
            }

            if (isset($field[self::RESERVED_INSIDE])) {
                $response[$parameterName] = $this->resolveStruct($field[self::RESERVED_INSIDE], $this->resolveArrayField($resource, $field[self::RESERVED_FIELD]));
            } else {
                $response[$parameterName] = $resource[$field[self::RESERVED_FIELD]];
            }
        }

        return $response;
    }

    /**
     * Leave field empty in response when incoming data is empty
     *
     * @param $resource
     * @param $field
     * @return bool
     */
    private function leaveEmpty($resource, $field): bool
    {
        return !isset($resource[$field[self::RESERVED_FIELD]])
            && array_key_exists('fill_if_empty', $field)
            && !$field['fill_if_empty'];
    }

    /**
     * @param $resource
     * @param string $fieldName
     * @return null[]
     */
    private function resolveArrayField($resource, string $fieldName): array
    {
        return $resource[$fieldName] ?? [null];
    }

    /**
     * @param $resource
     * @param string $fieldName
     * @return array|string|null
     */
    private function resolveField($resource, string $fieldName)
    {
        return $resource[$fieldName] ?? null;
    }

    /**
     * @param $field
     * @return string
     */
    private function resolveParameterName($field): string
    {
        return $field[self::RESERVED_ALIAS] ?? $field[self::RESERVED_FIELD];
    }

    /**
     * @param array $resource
     * @return array|string[]
     * @throws Exception
     */
    private function resolveResource(array $resource): array
    {
        $resourceClass = $this->resolveField($resource, self::RESERVED_CLASS);
        $resourceMethod = $this->resolveField($resource, self::RESERVED_METHOD);

        if (!$resourceClass) {
            throw new Exception("Resource must have a 'class' parameter");
        } elseif (!class_exists($resourceClass)) {
            throw new Exception("Class '$resourceClass' does not exist");
        }

        if (!$resourceMethod) {
            throw new Exception("Resource must have a 'method' parameter");
        } elseif (!method_exists($resourceClass, $resourceMethod)) {
            throw new Exception("Method '$resourceMethod' does not exist in class '$resourceClass'");
        }

        return [$resourceClass, $resourceMethod];
    }
}
