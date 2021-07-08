<?php

namespace Tests\Feature\Processors;

use App\Models\Eloquent\Translation;
use App\Support\Language;
use Exception;
use Illuminate\Contracts\Container\BindingResolutionException;

abstract class DeleteProcessorTestCase extends ProcessorTestCase
{
    /**
     * Determines if entity will only marked as deleted
     *
     * @var bool
     */
    public static bool $hasSoftDeletes = false;

    /**
     * Translation namespace for automatic test set up (if supported)
     *
     * @var string|null
     */
    public static ?string $translationNamespace = null;

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
    public function testItDeleteRecord(): void
    {
        $this->processor->processMessage($this->message);

        $this->assertDeletedEntity();
        $this->assertDeletedTranslationsIfSupports();
    }

    /**
     * Check is entity deleted or marked as deleted
     *
     * @noinspection PhpPossiblePolymorphicInvocationInspection
     */
    protected function assertDeletedEntity(): void
    {
        $entityId = $this->getEntityId();

        if (static::$hasSoftDeletes) {
            $this->assertEquals(1, $this->model->whereId($entityId)->value('is_deleted'));
        } else {
            $this->assertDatabaseMissing(get_class($this->model), ['id' => $entityId]);
        }
    }

    /**
     * Check is translations deleted if entity was truly deleted
     *
     * @noinspection PhpUndefinedMethodInspection
     */
    protected function assertDeletedTranslationsIfSupports(): void
    {
        if (!isset($this->translation) || static::$hasSoftDeletes) {
            return;
        }

        $entityId = $this->getEntityId();

        $this->assertDatabaseMissing(get_class($this->translation), [
            $this->model->translations()->getForeignKeyName() => $entityId,
        ]);
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

        $this->setUpTranslationIfExists();
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
    protected function setUpTranslationIfExists(): void
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
     * @param int $entityId
     * @return void
     */
    protected function setUpData(int $entityId): void
    {
        if (!static::$dataRoot) {
            $this->data['id'] = $entityId;
        } else {
            $this->data[static::$dataRoot]['id'] = $entityId;
        }
    }
}
