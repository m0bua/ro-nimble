<?php

namespace App\Support\IdeHelper\ModelHooks;

use Barryvdh\LaravelIdeHelper\Console\ModelsCommand;
use Barryvdh\LaravelIdeHelper\Contracts\ModelHookInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class TranslatableHook implements ModelHookInterface
{

    /**
     * @param ModelsCommand $command
     * @param Model $model
     * @noinspection PhpUnnecessaryCurlyVarSyntaxInspection
     */
    public function run(ModelsCommand $command, Model $model): void
    {
        // phpDoc translations collection and count
        if (method_exists($model, 'translations')) {
            $translationClassname = Str::afterLast(get_class($model->translations()->getRelated()), '\\');
            $command->setProperty('translations', "Collection|{$translationClassname}[]", true, false);
            $command->setProperty('translations_count', 'int|null', true, false);
        }

        // phpDoc translatable columns
        if (method_exists($model, 'getTranslatableProperties')) {
            foreach ($model->getTranslatableProperties() as $item) {
                $command->setProperty($item, 'string', true, true, "$item translation");
            }
        }
    }
}
