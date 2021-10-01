<?php

namespace App\Console\Commands\Index;

use App\Models\Elastic\ProducersModel;
use App\Models\Eloquent\Producer;
use App\Support\Language;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Throwable;

class IndexProducers extends IndexCommand
{
    /**
     * @var string
     */
    protected $signature = 'db:index-producers';

    /**
     * @var string
     */
    protected $description = 'Indexing producers from producers table';

    /**
     * @var ProducersModel
     */
    protected ProducersModel $elastic;

    /**
     * @var Producer
     */
    protected Producer $model;

    /**
     * IndexProducers constructor.
     * @param ProducersModel $elastic
     * @param Producer $model
     */
    public function __construct(ProducersModel $elastic, Producer $model)
    {
        $this->elastic = $elastic;
        $this->model = $model;

        parent::__construct();
    }

    /**
     * @inheritDoc
     */
    protected function proceed(): void
    {
        $this->elastic->setIndexVars();

        $this->elastic->createIndex(
            $this->elastic->getCreatingIndex(),
            $this->elastic->getIndexStructure()
        );

        $this->fillIndex();

        $this->elastic->updateAliases([
            $this->elastic->removeAliasAction($this->elastic->getDeletingIndex()),
            $this->elastic->addAliasAction($this->elastic->getCreatingIndex())
        ]);

        $this->elastic->deleteIndex($this->elastic->getDeletingIndex());
    }

    /**
     * Fill in index
     *
     * @throws Throwable
     */
    public function fillIndex(): void
    {
        DB::transaction(function () {
            $query = $this->buildQuery();
            $this->iterateQueryByCursor($query, [$this, 'operateWithEntity']);
        });
    }

    /**
     * @inheritDoc
     * @noinspection PhpUndefinedMethodInspection
     */
    protected function iterateQueryByCursor(Builder $query, callable $callback): void
    {
        /** @var Collection $entities */
        foreach ($query->trueCursor(500) as $entities) {
            $this->data = [];
            $this->bulkOperations = [
                'body' => [],
            ];

            foreach ($entities as $entity) {
                $callback($entity);
            }

            $this->buildElasticOperations();
            $this->executeElasticOperations();
        }
    }

    /**
     * @inheritDoc
     * @noinspection PhpIncompatibleReturnTypeInspection
     */
    protected function buildQuery(): Builder
    {
        return $this->model->select([
            'id',
            'name',
            'status',
        ]);
    }

    /**
     * @inheritDoc
     * @param Producer $entity
     */
    protected function operateWithEntity($entity): void
    {
        $title = trim($entity->getTranslation('title', Language::RU));

        $this->allIds[] = $entity->id;
        $this->data[$entity->id] = [
            'id' => $entity->id,
            'name' => $entity->name,
            'title' => $title,
            'status' => $entity->status,
            'first_symbol' => Str::of($title)->substr(0, 1)->upper(),
        ];
    }

    /**
     * @inheritDoc
     */
    protected function buildUpdateOperation(int $id): array
    {
        return [
            'update' => [
                '_index' => $this->elastic->getCreatingIndex(),
                '_id' => $id
            ],
        ];
    }

    /**
     * @inheritDoc
     */
    protected function buildScriptOperation(array $entity): array
    {
        return [
            'doc' => $entity,
            'doc_as_upsert' => true,
        ];
    }
}
