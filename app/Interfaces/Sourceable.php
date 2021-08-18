<?php

namespace App\Interfaces;

use App\Cores\ConsumerCore\Interfaces\MessageInterface;
use Illuminate\Database\Eloquent;

interface Sourceable
{
    public const DEFAULT_BATCH_SIZE = 1000;

    /**
     * Returns supported batch size
     *
     * @return int
     */
    public function batchSize(): int;

    /**
     * Process batch with provided callback
     *
     * @param callable $callback
     * @return bool
     */
    public function iterateBatch(callable $callback): bool;

    /**
     * Publish messages to queue
     *
     * @return bool
     */
    public function publishMessages(): bool;

    /**
     * Process message from queue
     *
     * @param MessageInterface $message
     * @return bool
     */
    public function processMessage(MessageInterface $message): bool;

    /**
     * Build query for fetch data from DB
     *
     * @return Eloquent\Builder
     */
    public function buildDbQuery(): Eloquent\Builder;

    /**
     * Prepare entity for message
     *
     * @param Eloquent\Model $entity
     * @return array
     */
    public function distributeEntity(Eloquent\Model $entity): array;

    /**
     * Build Elastic script for data painless migration
     *
     * @param array $params
     * @param string $lang
     * @return array
     */
    public function buildElasticScript(array $params = [], string $lang = 'painless'): array;

    /**
     * Build Elastic query for filter affected data
     *
     * @param array $params
     * @return array
     */
    public function buildElasticQuery(array $params = []): array;

    /**
     * Set missing entity as needs index
     *
     * @param int $entityId
     * @return void
     */
    public function setEntityAsNeedsIndex(int $entityId): void;
}
