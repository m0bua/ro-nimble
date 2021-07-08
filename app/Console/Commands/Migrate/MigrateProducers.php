<?php

namespace App\Console\Commands\Migrate;

use App\Models\Eloquent\Producer;
use Exception;
use Illuminate\Support\Arr;

class MigrateProducers extends MigrateCommand
{
    /**
     * @var string
     */
    protected $signature = 'db:migrate-producers';

    /**
     * @var string
     */
    protected $description = 'Migrate producers from db store to db nimble';

    /**
     * @var Producer
     */
    protected Producer $model;

    /**
     * MigrateProducers constructor.
     * @param Producer $model
     */
    public function __construct(Producer $model)
    {
        $this->model = $model;

        parent::__construct();
    }

    /**
     * @inheritDoc
     * @param Producer $entity
     * @throws Exception
     * @noinspection TypeUnsafeComparisonInspection
     */
    protected function processEntity($entity): void
    {
        $data = $entity->getRawOriginal();
        $data['disable_filter_series'] = $data['disable_filter_series'] ? 'true' : 'false';
        $data['show_background'] = $data['show_background'] ? 'true' : 'false';

        try {
            $this->model->create(Arr::only($data, $this->model->getFillable()));
        } catch (Exception $e) {
            if ($e->getCode() != 23505) {
                throw $e;
            }
        }

        $translatable = Arr::only($data, $this->model->getTranslatableProperties());

        $entity->setConnection('nimble')
            ->forceFill($translatable);
    }
}
