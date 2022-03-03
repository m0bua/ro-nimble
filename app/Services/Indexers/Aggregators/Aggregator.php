<?php

namespace App\Services\Indexers\Aggregators;

use Illuminate\Support\Collection;

interface Aggregator
{
    /**
     * Collect data with provided IDs
     *
     * @param Collection $ids
     * @return $this
     */
    public function aggregate(Collection $ids): self;

    /**
     * Decorate provided item with aggregated data
     *
     * @param object $item
     * @return object
     */
    public function decorate(object $item): object;

    /**
     * Get all aggregated data
     *
     * @return Collection
     */
    public function all(): Collection;

    /**
     * Get aggregated row by key (ID)
     *
     * @param int $key
     * @return mixed
     */
    public function get(int $key);
}
