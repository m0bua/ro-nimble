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
                if (isset($field['resource'])) {
                    list($resourceClass, $resourceMethod) = $this->checkResource($field);
                    $response[$key] = $resourceClass::$resourceMethod($this->resource[$fieldName]);
                }
            } else {
                $response[$field] = $this->resource[$field] ?? null;
            }
        }

        return $response;
    }

    /**
     * @param array $field
     * @return array
     * @throws Exception
     */
    private function checkResource(array $field): array
    {
        if (!is_array($field['resource'])) {
            throw new Exception('Field "resource" must be of type array');
        }

        if (!isset($field['resource']['class'])) {
            throw new Exception('Resource must have a "class" parameter');
        }

        if (!isset($field['resource']['method'])) {
            throw new Exception('Resource must have a "method" parameter');
        }

        return [$field['resource']['class'], $field['resource']['method']];
    }
}
