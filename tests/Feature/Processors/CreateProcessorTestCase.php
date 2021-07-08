<?php

namespace Tests\Feature\Processors;

use App\Models\Eloquent\Translation;
use App\Support\Language;
use Exception;
use Illuminate\Contracts\Container\BindingResolutionException;

abstract class CreateProcessorTestCase extends ProcessorTestCase
{
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
     * @return void
     */
    public function testItCanCreateRecord(): void
    {
        $this->processor->processMessage($this->message);

        $this->assertCreatedEntity();
        $this->assertCreatedTranslationsIfExists();
    }

    /**
     * Check is entity exists
     *
     * @return void
     */
    protected function assertCreatedEntity(): void
    {
        $this->assertDatabaseHas(get_class($this->model), $this->expected);
    }

    /**
     * Check is every translation exists
     *
     * @return void
     * @noinspection PhpUndefinedMethodInspection
     */
    protected function assertCreatedTranslationsIfExists(): void
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

        $this->setUpTranslationIfExists();
        $this->setUpData();
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
     * Seed data for AMQ Message
     *
     * @return void
     * @noinspection PhpPossiblePolymorphicInvocationInspection
     */
    protected function setUpData(): void
    {
        // Firstly, we seed non-translatable fields of entity
        $this->data[static::$dataRoot] = $this->model->factory()->make()->toArray();

        // and save them into expected too
        $this->expected = $this->data[static::$dataRoot];
        if ($this->withNeedsIndex) {
            $this->expected['needs_index'] = 1;
        }

        if ($this->withNeedsMigrate) {
            $this->expected['needs_migrate'] = 1;
        }

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
