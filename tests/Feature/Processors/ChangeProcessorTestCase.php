<?php

namespace Tests\Feature\Processors;

use App\Models\Eloquent\Translation;
use App\Support\Language;
use Exception;
use Illuminate\Contracts\Container\BindingResolutionException;

abstract class ChangeProcessorTestCase extends ProcessorTestCase
{
    /**
     * Translation namespace for automatic test set up (if supported)
     *
     * @var string|null
     */
    public static ?string $translationNamespace = null;

    /**
     * If entity doesn't has own key, these columns won't be re-seeded
     *
     * @var array|null
     */
    public static ?array $uniqueColumns = null;

    /**
     * Entity translations model (if supported)
     *
     * @var Translation|null
     */
    protected ?Translation $translation = null;

    /**
     * Default test case
     *
     * @throws Exception
     */
    public function testItCanChangeRecord(): void
    {
        $this->processor->processMessage($this->message);

        $this->assertChangedEntity();
        $this->assertChangedTranslationsIfExists();
    }

    /**
     * Check is entity exists
     *
     * @return void
     */
    protected function assertChangedEntity(): void
    {
        $this->assertDatabaseHas(get_class($this->model), $this->expected);
    }

    /**
     * Check is every translation exists
     *
     * @return void
     * @noinspection PhpUndefinedMethodInspection
     */
    protected function assertChangedTranslationsIfExists(): void
    {
        if (!isset($this->translation)) {
            return;
        }

        $entityId = $this->getEntityId();

        foreach ($this->model->getTranslatableProperties() as $translatableProperty) {
            $this->assertDatabaseHas(get_class($this->translation), [
                $this->model->translations()->getForeignKeyName() => $entityId,
                'lang' => Language::RU,
                'column' => $translatableProperty,
                'value' => $this->data['data'][$translatableProperty],
            ]);

            $this->assertDatabaseHas(get_class($this->translation), [
                $this->model->translations()->getForeignKeyName() => $entityId,
                'lang' => Language::UK,
                'column' => $translatableProperty,
                'value' => $this->data['data']['uk'][$translatableProperty],
            ]);
        }
    }

    /**
     * Setup the test environment.
     *
     * @return void
     * @throws BindingResolutionException|Exception
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->setUpTranslationModelIfExists();
        $entityId = $this->setUpDatabase();
        $this->setUpData($entityId);
        $this->setUpMessage();
    }

    /**
     * Setup processor, model and translation
     *
     * @return void
     * @throws BindingResolutionException
     */
    protected function setUpTranslationModelIfExists(): void
    {
        if (isset(static::$translationNamespace)) {
            $this->translation = $this->app->make(static::$translationNamespace);
        }
    }

    /**
     * Create entity for change and return its id
     *
     * @return int
     * @noinspection PhpPossiblePolymorphicInvocationInspection
     */
    protected function setUpDatabase(): int
    {
        $entity = $this->model->factory()->create();

        if ($this->translation && method_exists($this->model, 'getTranslatableProperties')) {
            foreach ($this->model->getTranslatableProperties() as $translatableProperty) {
                $entity->{$translatableProperty} = [
                    Language::RU => $this->faker->words(3, true),
                    Language::UK => $this->faker->words(3, true),
                ];
            }
        }

        return $entity->id;
    }

    /**
     * Seed data for AMQ Message
     *
     * @param int|null $entityId
     * @return void
     * @noinspection PhpPossiblePolymorphicInvocationInspection
     */
    protected function setUpData(int $entityId = null): void
    {
        // Firstly, we seed non-translatable fields of entity
        $this->data[static::$dataRoot] = $this->model->factory()
            ->make()
            ->toArray();

        if ($entityId) {
            $this->data[static::$dataRoot]['id'] = $entityId;
        }

        if (!static::$hasOwnId && static::$uniqueColumns) {
            $entity = $this->model->find($entityId);

            if ($entity) {
                foreach (static::$uniqueColumns as $column) {
                    $this->data[static::$dataRoot][$column] = $entity->{$column};
                }
            }

        }

        // and save them into expected too
        $this->expected = $this->data[static::$dataRoot];

        // We process aliases for this data
        foreach (static::$aliases as $messageField => $dbField) {
            if (isset($this->data[static::$dataRoot][$dbField])) {
                $this->data[static::$dataRoot][$messageField] = $this->data[static::$dataRoot][$dbField];
                unset($this->data[static::$dataRoot][$dbField]);
            }
        }

        // Secondly, we generate translatable fields
        if ($this->translation && method_exists($this->model, 'getTranslatableProperties')) {
            foreach ($this->model->getTranslatableProperties() as $translatableProperty) {
                $this->data[static::$dataRoot][$translatableProperty] = $this->faker->word();
                $this->data[static::$dataRoot]['uk'][$translatableProperty] = $this->faker->word();
            }
        }

        if (static::$dataRoot === null) {
            $this->data = $this->data[static::$dataRoot];
        }
    }
}
