<?php

namespace App\Services\Indexers\Aggregators;

use Illuminate\Support\Collection;
use LogicException;

abstract class AbstractAggregator implements Aggregator
{
    /**
     * Aggregated data
     *
     * @var Collection
     */
    protected Collection $data;

    /**
     * Flag indicates whether there is partial indexing
     *
     * @var bool
     */
    protected bool $isPartial;

    /**
     * @inheritDoc
     */
    public function aggregate(Collection $ids): Aggregator
    {
        $this->data = $this->prepare($ids);

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function all(): Collection
    {
        $this->checkData();
        return $this->data;
    }

    /**
     * @inheritDoc
     */
    public function get(int $key)
    {
        $this->checkData();
        return $this->data[$key] ?? null;
    }

    /**
     * Check is data set
     *
     * @return void
     */
    protected function checkData(): void
    {
        if (!isset($this->data)) {
            throw new LogicException('You may aggregate data first');
        }
    }

    /**
     * Get and prepare data
     *
     * @param Collection $ids
     * @return Collection
     */
    abstract protected function prepare(Collection $ids): Collection;

    /**
     * Decode data from JSON
     *
     * @param string $string
     * @return mixed
     * @noinspection JsonEncodingApiUsageInspection
     */
    protected function decode(string $string)
    {
        return json_decode($string, true);
    }

    /**
     * Setter for flag which indicates whether there is partial indexing
     *
     * @param bool $isPartial
     * @return void
     */
    public function setIsPartial(bool $isPartial)
    {
        $this->isPartial = $isPartial;
    }
}
