<?php

namespace Tests\Feature\Processors;

use App\Support\Language;

/**
 * Trait WithUpsert
 *
 * Only for use if 'change' event also can create entities
 *
 * @package Tests\Feature\Processors
 */
trait WithUpsert
{
    /**
     * Default test case
     *
     * @return void
     */
    public function testItCanCreateRecord(): void
    {
        $this->setUpData();
        $this->setUpMessage();

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
}
