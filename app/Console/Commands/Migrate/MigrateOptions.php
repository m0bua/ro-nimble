<?php

namespace App\Console\Commands\Migrate;

use App\Models\Eloquent\Option;
use Exception;
use Illuminate\Support\Arr;

class MigrateOptions extends MigrateCommand
{
    /**
     * @var string
     */
    protected $signature = 'db:migrate-options';

    /**
     * @var string
     */
    protected $description = 'Migrate options from db store to db nimble';

    /**
     * @var Option
     */
    protected Option $model;

    /**
     * MigrateOptions constructor.
     * @param Option $model
     */
    public function __construct(Option $model)
    {
        $this->model = $model;

        parent::__construct();
    }

    /**
     * @inheritDoc
     * @param Option $entity
     * @throws Exception
     */
    protected function processEntity($entity): void
    {
        $data = $entity->getRawOriginal();
        $data['affect_group_photo'] = $data['affect_group_photo'] ? 'true' : 'false';
        unset($data['copy_forbid']);

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
