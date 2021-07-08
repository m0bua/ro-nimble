<?php

namespace App\Support\IdeHelper\ModelHooks;

use Barryvdh\LaravelIdeHelper\Console\ModelsCommand;
use Barryvdh\LaravelIdeHelper\Contracts\ModelHookInterface;
use Illuminate\Database\Eloquent\Model;

class TranslatableHook implements ModelHookInterface
{

    public function run(ModelsCommand $command, Model $model): void
    {
        if (method_exists($model, 'getTranslatableProperties')) {
            $translatable = $model->getTranslatableProperties();

            foreach ($translatable as $item) {
                $command->setProperty($item, 'array<string>', true, true, "$item translations");
            }
        }
    }
}
