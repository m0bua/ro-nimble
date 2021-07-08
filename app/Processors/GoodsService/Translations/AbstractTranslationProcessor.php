<?php

namespace App\Processors\GoodsService\Translations;

use App\Cores\ConsumerCore\Interfaces\MessageInterface;
use App\Cores\ConsumerCore\Interfaces\ProcessorInterface;
use App\Cores\Shared\Codes;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use LogicException;

abstract class AbstractTranslationProcessor implements ProcessorInterface
{
    /**
     * Root key in data array, if null data doesn't be wrapped
     *
     * @var string|null
     */
    public static ?string $dataRoot = 'data';

    /**
     * Translations will be saved with this language
     * By default it is resolved from classname
     *
     * @var string|null
     */
    public static ?string $language = null;

    /**
     * If entity does not have own key, it will be resolved from enumerated columns
     *
     * @var array|null
     */
    public static ?array $compoundKey = null;

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
     * AbstractProcessor constructor.
     */
    public function __construct()
    {
        if (!static::$language) {
            static::$language = $this->resolveLanguage();
        }
    }

    /**
     * @inheritDoc
     */
    public function processMessage(MessageInterface $message): int
    {
        $this->setDataFromMessage($message);
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
        if (static::$dataRoot) {
            $this->data = (array)$message->getField(static::$dataRoot);
        } else {
            $this->data = (array)$message->getBody();
        }

        return $this->data;
    }

    /**
     * Get entity from DB
     *
     * @return Model
     * @noinspection PhpPossiblePolymorphicInvocationInspection
     */
    protected function resolveEntity(): Model
    {
        if (static::$compoundKey) {
            $key = Arr::only($this->data, static::$compoundKey);

            return $this->model->newInstance($key);
        }

        if (!isset($this->data['id'])) {
            throw new LogicException('ID can not be null');
        }

        return $this->model->findOrNew($this->data['id'])
            ->forceFill([
                'id' => $this->data['id'],
            ]);
    }

    /**
     * Get targeted language from processor classname
     *
     * @return string
     */
    protected function resolveLanguage(): string
    {
        return Str::of(static::class)
            ->afterLast('\\')
            ->before('Processor')
            ->substr(-2)
            ->lower();
    }

    /**
     * Save translations to DB
     *
     * @return bool
     */
    protected function saveTranslations(): bool
    {
        // resolve entity from payload
        $entity = $this->resolveEntity();

        // Throw exception if entity translations not supported
        if (!method_exists($entity, 'getTranslatableProperties') || !method_exists($entity, 'setTranslation')) {
            throw new LogicException('Model [' . get_class($this->model) . '] not translatable.');
        }

        // if entity doesn't exist and doesn't has own external ID
        if (static::$compoundKey) {
            return $this->saveTranslationsDirectlyFromRelated($entity);
        }

        return $this->saveTranslationsForEntity($entity);
    }

    /**
     * Save translations for entity with own ID
     *
     * @param Model $entity
     * @return bool
     */
    abstract protected function saveTranslationsForEntity(Model $entity): bool;

    /**
     * Save translations via translations model
     *
     * @param Model $entity
     * @return bool
     * @noinspection PhpUndefinedMethodInspection
     */
    protected function saveTranslationsDirectlyFromRelated(Model $entity): bool
    {
        /** @var Model|Builder $translationModel */
        $translationModel = $entity->translations()->getRelated();
        $compoundKey = Arr::only($this->data, static::$compoundKey);

        foreach ($entity->getTranslatableProperties() as $column) {
            $translation = $this->data[$column];
            if (!isset($translation)) {
                continue;
            }

            $translationModel->create([
                'lang' => static::$language,
                'column' => $column,
                'value' => $translation,
                'compound_key' => $compoundKey,
            ]);
        }

        return true;
    }

    /**
     * Disable DB checks about foreign key
     *
     * @param Model $model
     * @noinspection PhpUndefinedMethodInspection
     */
    protected static function disableForeignTriggerForEntity(Model $model): void
    {
        $table = $model->translations()->getRelated()->getTable();

        DB::statement("alter table $table disable trigger all");
    }

    /**
     * Enable DB checks about foreign key
     *
     * @param Model $model
     * @noinspection PhpUndefinedMethodInspection
     */
    protected static function enableForeignTriggerForEntity(Model $model): void
    {
        $table = $model->translations()->getRelated()->getTable();

        DB::statement("alter table $table enable trigger all");
    }
}
