<?php

namespace App\Processors\GoodsService\Translations;

use Illuminate\Database\Eloquent\Model;

abstract class SyncAbstractTranslationProcessor extends AbstractTranslationProcessor
{
    /**
     * Save translations for entity with own ID
     *
     * @param Model $entity
     * @return bool
     * @noinspection PhpUndefinedMethodInspection
     */
    protected function saveTranslationsForEntity(Model $entity): bool
    {
        foreach ($entity->getTranslatableProperties() as $column) {
            $translation = $this->data[$column] ?? null;

            if (!isset($translation)) {
                continue;
            }

            $entity->setTranslation(static::$language, $column, $translation);
        }

        return true;
    }
}
