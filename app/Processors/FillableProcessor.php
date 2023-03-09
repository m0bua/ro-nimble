<?php

namespace App\Processors;

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

abstract class FillableProcessor implements ProcessorInterface
{

    protected string $relationField = '';
    protected string $getRelation = '';
    protected string $setRelation = '';
    protected string $getProperties = '';

    /**
     * Root key in data array, if null data doesn't be wrapped
     *
     * @var string
     */
    protected string $dataRoot = 'data';

    /**
     * @var string
     */
    protected string $fillableField;

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
        $this->fillableField = $this->parseFillable($message->getRoutingKey());
        $this->saveFillable();

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
     * Get targeted fillable from routing key
     *
     * @param string $routingKey
     * @return string
     */
    protected function parseFillable(string $routingKey): string
    {
        $keywords = explode('.', ucwords($routingKey, '.'));
        $thirdPart = str_replace('_', '', ucwords($keywords[2], '_'));

        return Str::of($thirdPart)->substr(-2, 2)->lower();
    }

    /**
     * Save fillable to DB
     *
     * @return bool
     */
    protected function saveFillable(): bool
    {
        // resolve entity from payload
        $entity = $this->findEntity();

        // if entity doesn't exist and doesn't have own external ID
        if (!empty($this->compoundKey)) {
            return $this->saveFillableDirectlyFromRelated($entity);
        }

        return $this->saveFillableForEntity($entity);
    }

    /**
     * Save fillable for entity with own ID
     *
     * @param Model $entity
     * @return bool
     * @noinspection PhpUndefinedMethodInspection
     */
    protected function saveFillableForEntity(Model $entity): bool
    {
        foreach ($this->execModelMethod($entity, $this->getProperties) as $column) {
            $value = $this->data[$column] ?? null;

            if (!isset($value)) {
                continue;
            }

            $this->execModelMethod($entity, $this->setRelation, $this->fillableField, $column, $value);
        }

        return true;
    }

    /**
     * Save fillable via fillable model
     *
     * @param Model $entity
     * @return bool
     * @noinspection PhpUndefinedMethodInspection
     */
    protected function saveFillableDirectlyFromRelated(Model $entity): bool
    {
        /** @var Model|Builder $fillableModel */
        $fillableModel = $this->execModelMethod($entity, $this->getRelation)->getRelated();
        $compoundKey = Arr::only($this->data, $this->compoundKey);

        foreach ($this->execModelMethod($entity, $this->getProperties) as $column) {
            $fillable = $this->data[$column];
            if (!isset($fillable)) {
                continue;
            }

            $fillableModel->create([
                $this->relationField => $this->fillableField,
                'column' => $column,
                'value' => $fillable,
                'compound_key' => $compoundKey,
            ]);
        }

        return true;
    }

    /**
     * Throw exception if model doesn't support method
     *
     * @param Model $model
     * @param string $method
     * @param mixed $args
     * @return mixed
     */
    protected function execModelMethod(Model $model, string $method, ...$args)
    {
        if (empty($method)) {
            return;
        }

        if (!method_exists($model, $method)) {
            throw new RuntimeException('Model [' . get_class($model) . '] does not support ' . $method . '() method');
        }

        return $model->$method(...$args);
    }
}
