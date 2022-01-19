<?php

namespace App\Processors\GoodsService\Translations;

use App\Cores\ConsumerCore\Interfaces\MessageInterface;
use App\Cores\ConsumerCore\Interfaces\ProcessorInterface;
use App\Cores\Shared\Codes;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use LogicException;
use RuntimeException;

abstract class TranslationProcessor implements ProcessorInterface
{
    /**
     * Root key in data array, if null data doesn't be wrapped
     *
     * @var string
     */
    protected string $dataRoot = 'data';

    /**
     * Translations will be saved with this language
     * By default it is resolved from routing key
     *
     * @var string
     */
    protected string $language;

    /**
     * If entity does not have own key, it will be resolved from enumerated columns
     *
     * @var array
     */
    protected array $compoundKey;

    /**
     * Message data for processing
     *
     * @var array
     */
    protected array $data;

    /**
     * Eloquent model for processing
     *
     * @var Model
     */
    protected Model $model;

    /**
     * @inheritDoc
     * @throws Exception
     */
    public function processMessage(MessageInterface $message): int
    {
        $this->setDataFromMessage($message);
        $this->language = $this->parseLanguage($message->getRoutingKey());
        $this->saveTranslations();

        return Codes::SUCCESS;
    }

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
     * Get entity from DB
     *
     * @return Model
     */
    protected function findEntity(): Model
    {
        if (!empty($this->compoundKey)) {
            $key = Arr::only($this->data, $this->compoundKey);

            return $this->model->newInstance($key);
        }

        if (!isset($this->data['id'])) {
            throw new LogicException('ID can not be null');
        }

        return $this->model->newInstance(['id' => $this->data['id']]);
    }

    /**
     * Get targeted language from routing key
     *
     * @param string $routingKey
     * @return string
     */
    protected function parseLanguage(string $routingKey): string
    {
        $keywords = explode('.', ucwords($routingKey, '.'));
        $thirdPart = str_replace('_', '', ucwords($keywords[2], '_'));

        return Str::of($thirdPart)->substr(-2, 2)->lower();
    }

    /**
     * Save translations to DB
     *
     * @return bool
     */
    protected function saveTranslations(): bool
    {
        // resolve entity from payload
        $entity = $this->findEntity();

        // Throw exception if entity translations not supported
        if (!method_exists($entity, 'getTranslatableProperties') || !method_exists($entity, 'setTranslation')) {
            throw new LogicException('Model [' . get_class($this->model) . '] not translatable.');
        }

        // if entity doesn't exist and doesn't have own external ID
        if (!empty($this->compoundKey)) {
            return $this->saveTranslationsDirectlyFromRelated($entity);
        }

        return $this->saveTranslationsForEntity($entity);
    }

    /**
     * Save translations for entity with own ID
     *
     * @param Model $entity
     * @return bool
     * @noinspection PhpUndefinedMethodInspection
     */
    protected function saveTranslationsForEntity(Model $entity): bool
    {
        $this->checkModel($entity);

        foreach ($entity->getTranslatableProperties() as $column) {
            $translation = $this->data[$column] ?? null;

            if (!isset($translation)) {
                continue;
            }

            $entity->setTranslation($this->language, $column, $translation);
        }

        return true;
    }

    /**
     * Save translations via translations model
     *
     * @param Model $entity
     * @return bool
     * @noinspection PhpUndefinedMethodInspection
     */
    protected function saveTranslationsDirectlyFromRelated(Model $entity): bool
    {
        $this->checkModel($entity);

        /** @var Model|Builder $translationModel */
        $translationModel = $entity->translations()->getRelated();
        $compoundKey = Arr::only($this->data, $this->compoundKey);

        foreach ($entity->getTranslatableProperties() as $column) {
            $translation = $this->data[$column];
            if (!isset($translation)) {
                continue;
            }

            $translationModel->create([
                'lang' => $this->language,
                'column' => $column,
                'value' => $translation,
                'compound_key' => $compoundKey,
            ]);
        }

        return true;
    }

    /**
     * Throw exception if model doesn't support translations
     *
     * @param Model $model
     * @return void
     */
    protected function checkModel(Model $model): void
    {
        if (!method_exists($model, 'translations') || !method_exists($model, 'getTranslatableProperties')) {
            throw new RuntimeException('Model [' . get_class($model) . '] does not support translations');
        }
    }
}
