<?php


namespace App\Http\Resources;


use Illuminate\Http\Resources\Json\JsonResource;

class BaseResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        $response = [];
        foreach ($this->getResourceFields() as $field) {
            if (isset($this->resource[$field])) {
                $response[$field] = $this->resource[$field];
            } else {
                $response[$field] = null;
            }
        }

        return $response;
    }

    /**
     * Returns list of fields for resource
     *
     * @return array
     */
    public function getResourceFields(): array
    {
        return [];
    }
}
