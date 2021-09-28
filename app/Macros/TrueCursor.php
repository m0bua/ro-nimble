<?php

namespace App\Macros;

use Generator;
use Illuminate\Database\Connection;
use Illuminate\Database\ConnectionInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use IteratorAggregate;
use Throwable;

class TrueCursor implements IteratorAggregate
{
    /**
     * Builder instance
     *
     * @var Builder
     */
    protected Builder $query;

    /**
     * Working model
     *
     * @var Model
     */
    protected Model $model;

    /**
     * Database connection
     *
     * @var Connection|ConnectionInterface
     */
    protected Connection $connection;

    /**
     * Cursor name
     *
     * @var string
     */
    protected string $cursor;

    /**
     * Count of fetched rows per iteration
     *
     * @var int
     */
    protected int $count;

    /**
     * @param Builder $query
     * @param int $count
     */
    public function __construct(Builder $query, int $count = 1)
    {
        $this->query = clone $query;
        $this->model = $query->getModel();
        $this->connection = $this->model->getConnection();
        $this->count = $count;
    }

    /**
     * @inerhitDoc
     * @return Generator|Model|Collection
     * @throws Throwable
     */
    public function getIterator(): Generator
    {
        foreach ($this->iterate($this->count) as $rows) {
            if ($this->count === 1) {
                yield $rows->first();
            } else {
                yield $rows;
            }
        }
    }

    /**
     * Iterate rows from cursor
     *
     * @param int $count
     * @return Generator|Collection
     * @throws Throwable
     */
    protected function iterate(int $count = 1): Generator
    {
        $cursorClosed = false;

        try {
            $this->connection->beginTransaction();
            $this->declareCursor();

            while ($rows = $this->fetchForward($count)) {
                yield collect($rows)->map(fn($row) => $this->model->newFromBuilder($row));
            }

            $this->closeCursor();
            $this->connection->commit();
            $cursorClosed = true;
        } finally {
            if (!$cursorClosed) {
                $this->connection->rollback();
            }
        }
    }

    /**
     * @return string
     */
    private function getCursor(): string
    {
        if (!isset($this->cursor)) {
            $this->cursor = 'cursor_' . Str::random();
        }

        return $this->cursor;
    }

    private function declareCursor(): void
    {
        $this->connection->statement("declare {$this->getCursor()} cursor for {$this->query->toSql()}", $this->query->getBindings());
    }

    private function fetchForward($count = 1): array
    {
        return $this->connection->select(sprintf("fetch forward %d from {$this->getCursor()}", $count));
    }

    private function closeCursor(): void
    {
        $this->connection->unprepared("close {$this->getCursor()}");
    }
}
