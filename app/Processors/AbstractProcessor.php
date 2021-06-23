<?php

namespace App\Processors;

use App\Cores\ConsumerCore\Interfaces\MessageInterface;
use App\Cores\ConsumerCore\Interfaces\ProcessorInterface;

abstract class AbstractProcessor implements ProcessorInterface
{
    /**
     * Process message from RabbitMQ
     *
     * @param MessageInterface $message
     * @return int
     */
    abstract public function processMessage(MessageInterface $message): int;

    /**
     * Returns array of data for save
     *
     * @param array $data
     * @param array $aliases
     * @return array
     */
     protected function prepareData(array $data, array $aliases = []): array
     {
         if (isset($this->model) && method_exists($this->model, 'getFillable')) {
             $fields = $this->model->getFillable();
         } else {
             $fields = array_keys($data);
         }

         if (!is_assoc($aliases)) {
             $aliases = [];
         }

         $prepared = [];

         foreach ($fields as $field) {
             $fieldName = in_array($field, $aliases) ? array_search($field, $aliases) : $field;

             $prepared[$field] = $this->resolveField($data[$fieldName] ?? null);
         }

         return $prepared;
     }

    /**
     * @param $field
     * @return array|float|int|string|null
     */
    protected function resolveField($field)
    {
        if (is_null($field)) {
            return null;
        } elseif (is_bool($field) || in_array($field, ['true', 'false'], true)) {
            return $field === true || $field === 'true' ? 'true' : 'false';
        } elseif (is_string($field)) {
            return $field;
        } elseif (is_object($field)) {
            return (array)$field;
        } elseif (is_int($field) || is_float($field)) {
            return is_int($field) ? (int)$field : (float)$field;
        }

        return $field;
    }
}
