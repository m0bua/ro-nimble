<?php

namespace App\Console\Commands\Index;

use App\Console\Commands\Command;
use Illuminate\Database\Eloquent\Builder;
use Throwable;

abstract class IndexCommand extends Command
{
    /**
     * @var array
     */
    protected array $allIds = [];

    /**
     * @var array
     */
    protected array $erroredIds = [];

    /**
     * @var array
     */
    protected array $data = [];

    /**
     * @var array
     */
    protected array $bulkOperations = [
        'body' => [],
    ];

    /**
     * @inheritDoc
     * @throws Throwable
     */
    protected function proceed(): void
    {
        $query = $this->buildQuery();
        $this->iterateQueryByCursor($query, [$this, 'operateWithEntity']);

        $this->buildElasticOperations();

        if ($this->bulkOperations['body']) {
            $result = $this->executeElasticOperations();
            $this->processElasticResult($result);
            $this->markEntitiesAsIndexed();
        }
    }

    /**
     * Build base query
     *
     * @return Builder
     */
    abstract protected function buildQuery(): Builder;

    /**
     * Cursor query as single entity with callback
     *
     * @param Builder $query
     * @param callable $callback
     */
    protected function iterateQueryByCursor(Builder $query, callable $callback): void
    {
        foreach ($query->cursor() as $entity) {
            $callback($entity);
        }
    }

    /**
     * Fill data
     *
     * @param $entity
     */
    abstract protected function operateWithEntity($entity): void;

    /**
     * Build Elasticsearch operations
     */
    protected function buildElasticOperations(): void
    {
        foreach ($this->data as $id => $entity) {
            $this->bulkOperations['body'][] = $this->buildUpdateOperation($id);
            $this->bulkOperations['body'][] = $this->buildScriptOperation($entity);
        }
    }

    /**
     * Build update operation
     *
     * @param int $id
     * @return array
     */
    abstract protected function buildUpdateOperation(int $id): array;

    /**
     * Build script operation
     *
     * @param array $entity
     * @return array
     */
    abstract protected function buildScriptOperation(array $entity): array;

    /**
     * Execute bulk operations
     *
     * @return array
     */
    protected function executeElasticOperations(): array
    {
        if ($this->bulkOperations['body']) {
            return $this->elastic->bulk($this->bulkOperations);
        }

        return [
            'Empty body'
        ];
    }

    /**
     * Check result and fill errored ids
     *
     * @param array $result
     * @return bool Return true if all of items executed successfully
     */
    protected function processElasticResult(array $result): bool
    {
        $isBulkOperationSuccessful = true;

        if (!empty($result['errors'])) {
            foreach ($result['items'] as $item) {
                if ($item['update']['status'] !== 200) {
                    $isBulkOperationSuccessful = false;
                    $this->erroredIds[] = (int)$item['update']['_id'];
                }
            }
        }

        return $isBulkOperationSuccessful;
    }

    /**
     * Mark entities as indexed in DB
     */
    protected function markEntitiesAsIndexed(): void
    {
        $onlyProcessedIds = array_diff($this->allIds, $this->erroredIds);

        foreach (array_chunk($onlyProcessedIds, 500) as $ids) {
            $this->model->whereIn('id', $ids)->update(['needs_index' => 0]);
        }
    }

    /**
     * Mark errored entities as not indexed in DB
     */
    protected function markErroredEntities(): void
    {
        foreach (array_chunk($this->erroredIds, 500) as $ids) {
            $this->model->whereIn('id', $ids)->update(['needs_index' => 1]);
        }
    }
}
