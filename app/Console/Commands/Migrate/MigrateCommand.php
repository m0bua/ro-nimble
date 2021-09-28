<?php

namespace App\Console\Commands\Migrate;

use App\Console\Commands\Command;
use Illuminate\Database\Eloquent\Builder;
use Throwable;

abstract class MigrateCommand extends Command
{
    /**
     * @inheritDoc
     * @throws Throwable
     */
    protected function proceed(): void
    {
        $query = $this->buildQuery();
        $this->iterateQueryByCursor($query, [$this, 'processEntity']);
    }

    /**
     * @return Builder
     */
    protected function buildQuery(): Builder
    {
        return $this->model->on('store');
    }

    /**
     * @param Builder $query
     * @param callable $callback
     * @noinspection PhpUndefinedMethodInspection
     */
    protected function iterateQueryByCursor(Builder $query, callable $callback): void
    {
        foreach ($query->trueCursor() as $entity) {
            $callback($entity);
        }
    }

    /**
     * @param $entity
     */
    abstract protected function processEntity($entity): void;
}
