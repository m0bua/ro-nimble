<?php

namespace Tests\Feature\Processors\GoodsService\Translations;

use App\Models\Eloquent\Translation;
use App\Support\Language;
use Exception;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use LogicException;
use Tests\Feature\Processors\ProcessorTestCase as TestCase;

abstract class TranslationProcessorTestCase extends TestCase
{
    /**
     * Language for check
     *
     * @var string
     */
    public static string $language = Language::UK;

    /**
     * If entity does not have own key, it will be resolved from enumerated columns
     *
     * @var array|null
     */
    public static ?array $compoundKey = null;

    /**
     * Translation namespace for automatic test set up
     *
     * @var string
     */
    public static string $translationNamespace;

    /**
     * Entity translations model
     *
     * @var Translation
     */
    protected Translation $translation;

    /**
     * If true => only can create entity, if false => update
     *
     * @var bool
     */
    protected bool $isActionCreate;

    /**
     * Check is all translations saved
     *
     * @return void
     * @noinspection PhpUndefinedMethodInspection
     */
    protected function assertTranslationsExists(): void
    {
        if (!static::$compoundKey) {
            $entityId = $this->getEntityId();
        }

        foreach ($this->getTranslationsData() as $column => $value) {
            $this->assertDatabaseHas($this->translation, [
                $this->model->translations()->getForeignKeyName() => $entityId ?? null,
                'lang' => static::$language,
                'column' => $column,
                'value' => $value,
            ]);
        }
    }

    /**
     * Check is all translations not updated
     *
     * @return void
     * @noinspection PhpUndefinedMethodInspection
     */
    protected function assertTranslationsMissing(): void
    {
        if (!static::$compoundKey) {
            $entityId = $this->getEntityId();
        }

        foreach ($this->getTranslationsData() as $column => $value) {
            $this->assertDatabaseMissing($this->translation, [
                $this->model->translations()->getForeignKeyName() => $entityId ?? null,
                'lang' => static::$language,
                'column' => $column,
                'value' => $value,
            ]);
        }
    }

    /**
     * Prepare data and execute processor action
     *
     * @param bool $withExistingEntity
     * @param bool $withTranslations
     * @throws Exception
     */
    protected function execute(bool $withExistingEntity, bool $withTranslations): void
    {
        if ($withExistingEntity) {
            $entityKey = $this->setUpEntity($withTranslations);
        }

        $this->setUpData($entityKey ?? null, !isset($entityKey) && $withTranslations);
        $this->setUpMessage();
        $this->processor->processMessage($this->message);
    }

    /**
     * @inheritDoc
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->isActionCreate = Str::of(get_class($this->processor))->afterLast('\\')->startsWith('Create');

        $this->setUpTranslation();
    }

    /**
     * Setup processor, translation
     *
     * @return void
     * @throws BindingResolutionException
     */
    protected function setUpTranslation(): void
    {
        $this->translation = $this->app->make(static::$translationNamespace);
    }

    /**
     * Creates entity end returns its ID
     *
     * @return array|int
     * @noinspection PhpPossiblePolymorphicInvocationInspection
     */
    protected function setUpEntity(bool $withTranslations = false)
    {
        $entity = $this->model->factory()->create();

        if ($withTranslations) {
            foreach ($entity->getTranslatableProperties() as $property) {
                $entity->setTranslation(static::$language, $property, $this->faker->words(2, true));
            }
        }

        if (static::$compoundKey) {
            return $entity->only(static::$compoundKey);
        }

        return $entity->id;
    }

    /**
     * Seed data for AMQ Message
     *
     * @param int|array|null $entityKey
     * @param bool $withFakeTranslations
     * @return void
     */
    protected function setUpData($entityKey = null, bool $withFakeTranslations = false): void
    {
        if (!method_exists($this->model, 'getTranslatableProperties')) {
            throw new LogicException('Model [' . get_class($this->model) . '] not translatable');
        }

        if (static::$compoundKey) {
            if (is_array($entityKey)) {
                $this->data[static::$dataRoot] = $entityKey;
            } else {
                foreach (static::$compoundKey as $key) {
                    $this->data[static::$dataRoot][$key] = $this->faker->numberBetween(1, 10000);
                }
            }
        } else {
            $this->data[static::$dataRoot]['id'] = $entityKey ?? $this->faker->numberBetween(1, 5000);
            if ($withFakeTranslations) {
                $entity = $this->model->newInstance(['id' => $this->data[static::$dataRoot]['id']]);

                static::disableForeignTriggerForEntity($entity);
                foreach ($this->model->getTranslatableProperties() as $property) {
                    /** @noinspection PhpUndefinedMethodInspection */
                    $entity->setTranslation(static::$language, $property, $this->faker->words(2, true));
                }
                static::enableForeignTriggerForEntity($entity);
            }
        }

        foreach ($this->model->getTranslatableProperties() as $property) {
            $this->data[static::$dataRoot][$property] = $this->faker->words(2, true);
        }

        if (static::$dataRoot === null) {
            $this->data = $this->data[static::$dataRoot];
        }
    }

    /**
     * Return entity ID
     *
     * @return int
     */
    protected function getEntityId(): int
    {
        return $this->getData()['id'] ?? 0;
    }

    /**
     * Get all data
     *
     * @return array
     */
    protected function getData(): array
    {
        if (static::$dataRoot) {
            return $this->data[static::$dataRoot];
        }

        return $this->data;
    }

    /**
     * Get key data
     *
     * @return array
     */
    protected function getKeyData(): array
    {
        $data = $this->getData();

        if (static::$compoundKey) {
            return Arr::only($data, static::$compoundKey);
        }

        return [
            'id' => $data['id'],
        ];
    }

    /**
     * Get translations data
     *
     * @return array
     */
    protected function getTranslationsData(): array
    {
        return collect($this->getData())
            ->filter(function ($value, string $key) {
                return $key !== 'id' && !in_array($key, static::$compoundKey ?? [], true);
            })
            ->toArray();
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
