<?php

namespace App\Processors;

use App\Cores\ConsumerCore\Interfaces\MessageInterface;
use App\Cores\ConsumerCore\Interfaces\ProcessorInterface;
use App\Support\Language;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;

abstract class Processor implements ProcessorInterface
{
    /**
     * Aliases for correct saving data
     * @example rank => producer_rank
     * 'rank' will be saved as 'producer_rank'
     *
     * @var array
     */
    protected array $aliases = [];

    /**
     * Root key in data array, if empty data doesn't be wrapped
     *
     * @var string
     */
    protected string $dataRoot = 'data';

    /**
     * If entity does not have own key, it will be resolved from enumerated columns
     *
     * @var array
     */
    protected array $compoundKey;

    /**
     * List of languages for save
     *
     * @var array
     */
    protected array $languages = [
        Language::UK,
    ];

    /**
     * Message data for process
     *
     * @var array
     */
    protected array $data;


    /**
     * Eloquent model
     *
     * @var Model
     */
    protected Model $model;

    /**
     * @inheritDoc
     */
    abstract public function processMessage(MessageInterface $message): int;

    /**
     * Unwrap message data
     *
     * @param MessageInterface $message
     * @return array
     */
    protected function setDataFromMessage(MessageInterface $message): array
    {
        if (!empty($this->dataRoot)) {
            $this->data = (array)$message->getField($this->dataRoot);
        } else {
            $this->data = (array)$message->getBody();
        }

        return $this->data;
    }

    /**
     * Some actions before processing message
     *
     * @return void
     */
    protected function beforeProcess(): void
    {
        //
    }

    /**
     * Some actions after processing message
     *
     * @return void
     */
    protected function afterProcess(): void
    {
        //
    }

    /**
     * Returns array of data for save
     *
     * @return array
     */
    protected function prepareData(): array
    {
        if (isset($this->model) && method_exists($this->model, 'getFillable')) {
            $fields = $this->model->getFillable();
        } else {
            $fields = array_keys($this->data);
        }

        $prepared = [];

        foreach ($fields as $field) {
            $fieldName = in_array($field, $this->aliases, true) ? array_search($field, $this->aliases, true) : $field;

            if (isset($this->data[$fieldName])) {
                $prepared[$field] = $this->resolveField($this->data[$fieldName]);
            }
        }

        return $prepared;
    }

    /**
     * Transform field correctly for save
     *
     * @param mixed $field
     * @return array|float|int|string|null
     */
    protected function resolveField($field)
    {
        if (is_null($field)) {
            return null;
        }

        if (is_bool($field) || in_array($field, ['true', 'false'], true)) {
            return $field === true || $field === 'true' ? 'true' : 'false';
        }

        if (is_string($field)) {
            return $field;
        }

        if (is_object($field)) {
            return (array)$field;
        }

        if (is_int($field) || is_float($field)) {
            return is_int($field) ? (int)$field : (float)$field;
        }

        return $field;
    }

    /**
     * Save entity translations
     *
     * @return void
     */
    protected function saveTranslations(): void
    {
        if (!method_exists($this->model, 'getTranslatableProperties')) {
            return;
        }

        $translatable = $this->model->getTranslatableProperties();
        if (empty($translatable)) {
            return;
        }

        $translations = Arr::only($this->data, $translatable);
        $entity = $this->resolveEntity();

        if (!method_exists($entity, 'setTranslation')) {
            return;
        }

        $entity->forceFill($translations);

        foreach ($this->languages as $language) {
            $translations = $this->data[$language] ?? [];

            foreach ($translations as $column => $translation) {
                if (!in_array($column, $translatable, true)) {
                    continue;
                }

                $entity->setTranslation($language, $column, $translation);
            }
        }
    }

    /**
     * Resolve entity for process with
     *
     * @return Model
     */
    protected function resolveEntity(): Model
    {
        if (!empty($this->compoundKey)) {
            $key = Arr::only($this->prepareData(), $this->compoundKey);
            return $this->model->query()->where($key)->firstOrNew($key);
        }

        return $this->model->newInstance(['id' => $this->data['id']]);
    }
}
