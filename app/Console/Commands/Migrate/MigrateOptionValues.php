<?php

namespace App\Console\Commands\Migrate;

use App\Models\Eloquent\OptionValue;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;

class MigrateOptionValues extends MigrateCommand
{
    /**
     * @var string
     */
    protected $signature = 'db:migrate-options-values';

    /**
     * @var string
     */
    protected $description = 'Migrate options values from db store to db nimble';

    /**
     * @var OptionValue
     */
    protected OptionValue $model;

    /**
     * MigrateOptionValues constructor.
     * @param OptionValue $model
     */
    public function __construct(OptionValue $model)
    {
        $this->model = $model;

        parent::__construct();
    }

    /**
     * @return Builder
     */
    protected function buildQuery(): Builder
    {
        return (clone $this->model)->bind('options_values', 'store')->newQuery();
    }

    /**
     * @inheritDoc
     * @param OptionValue $entity
     * @throws Exception
     * @noinspection TypeUnsafeComparisonInspection
     */
    protected function processEntity($entity): void
    {
        $data = $entity->getRawOriginal();

        try {
            $this->model->create(Arr::only($data, $this->model->getFillable()));
        } catch (Exception $e) {
            if ($e->getCode() != 23505) {
                throw $e;
            }
        }

        $translatable = Arr::only($data, $this->model->getTranslatableProperties());

        $entity->bind('option_values', 'nimble')
            ->forceFill($translatable);
    }
}
