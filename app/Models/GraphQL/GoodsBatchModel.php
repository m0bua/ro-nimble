<?php

declare(strict_types=1);

namespace App\Models\GraphQL;

use App\Traits\GSDefaultSelectionTrait;
use Closure;
use Exception;

class GoodsBatchModel extends GraphQL
{
    use GSDefaultSelectionTrait;

    /** @var int */
    public const DEFAULT_BATCH_SIZE = 100;

    /** @var string */
    protected string $whereInField = 'id';

    /** @var int */
    protected int $batchSize = self::DEFAULT_BATCH_SIZE;

    /**
     * GoodsBatchModel constructor.
     * @param string|null $whereInField
     * @param int|null $batchSize
     */
    public function __construct(?string $whereInField = null, ?int $batchSize = null)
    {
        if ($whereInField) {
            $this->whereInField = $whereInField;
        }

        if ($batchSize) {
            $this->batchSize = $batchSize;
        }

        parent::__construct();
    }

    /** @inheritDoc */
    public function serviceName(): string
    {
        return 'goods';
    }

    /** @inheritDoc */
    public function entityName(): string
    {
        return 'goodsBatch';
    }

    /**
     * @param string $whereInField
     * * @return self
     */
    public function setWhereInField(string $whereInField): self
    {
        $this->whereInField = $whereInField;
        return $this;
    }

    /**
     * @param int $batchSize
     * @return self
     */
    public function setBatchSize(int $batchSize): self
    {
        $this->batchSize = $batchSize;
        return $this;
    }

    /**
     * @param string $whereInField
     * @param int $batchSize
     * @return $this
     */
    public function setBatchConfig(string $whereInField, int $batchSize): self
    {
        $this->whereInField = $whereInField;
        $this->batchSize = $batchSize;
        return $this;
    }

    /**
     * @param array $goodsIds
     * @return array
     * @throws Exception
     */
    public function getByIds(array $goodsIds): array
    {
        $this->query->setArguments(
            $this->whereIn('id', $goodsIds)
        );

        return $this->get();
    }

    /**
     * @param array $goodsIds
     * @return array
     * @throws Exception
     * @noinspection PhpUnused
     */
    public function getDefaultDataByGoodsIds(array $goodsIds): array
    {
        $this->query->setSelectionSet([
            $this->query('nodes')->setSelectionSet($this->defaultSelectionSet())
        ]);

        return $this->getByIds($goodsIds)['nodes'];
    }

    /**
     * @param array $ids
     * @param Closure $callback
     * @throws Exception
     */
    public function getByBatch(array $ids, Closure $callback)
    {
        $batchId = 0;
        do {
            $this->query
                ->setSelectionSet([
                    $this->query('nodes')->setSelectionSet($this->defaultSelectionSet()),
                    $this->query('batchInfo')->setSelectionSet(['batchSize', 'lastID']),
                ])
                ->setArguments(array_merge(
                    $this->whereIn($this->whereInField, $ids),
                    $this->batch($this->batchSize, $batchId)
                ));

            $result = $this->get();

            if (!isset($result['batchInfo']) || $result['batchInfo']['batchSize'] == 0) {
                break;
            }

            $callback($result['nodes']);

            $batchId = $result['batchInfo']['lastID'];
        } while ($result['batchInfo']['batchSize'] == $this->batchSize);
    }
}
