<?php


namespace App\Http\Interfaces;


interface ResourceInterface
{
    /**
     * Returns list of fields for resource
     *
     * @return array
     */
    public function getResourceFields(): array;
}
