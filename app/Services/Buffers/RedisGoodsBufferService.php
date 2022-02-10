<?php

namespace App\Services\Buffers;

use App\Interfaces\GoodsBufferInterface;
use Illuminate\Support\Facades\Redis;
use Generator;

class RedisGoodsBufferService implements GoodsBufferInterface
{
    /**
     * Name of redis set
     */
    private const SET_NAME = 'index:goods';

    /**
     * @var int|mixed
     */
    private int $maxBatch;

    public function __construct()
    {
        $this->maxBatch = env("MAX_INDEXING_BATCH", 100);
    }

    /**
     * @param int $productId
     * @return void
     */
    public function add(int $productId): void
    {
        Redis::sadd(self::SET_NAME, $productId);
    }

    /**
     * @param array $goodsIds
     * @return void
     */
    public function radd(array $goodsIds): void
    {
        Redis::sadd(self::SET_NAME, $goodsIds);
    }

    /**
     * @return Generator
     */
    public function scan(): Generator
    {
        [$cursor, $goodsIds] = Redis::sscan('index:goods', 0, ['count' => $this->maxBatch]);
        do {
            $iterator = $cursor;
            yield $goodsIds;
            Redis::srem('index:goods', $goodsIds);
            [$cursor, $goodsIds] = Redis::sscan('index:goods', $iterator, ['count' => $this->maxBatch]);
        } while ($iterator !== 0);
    }
}
