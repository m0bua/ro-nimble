<?php

namespace App\Services\Buffers;

use App\Interfaces\GoodsBuffer;
use Generator;
use Illuminate\Support\Facades\Redis;

class RedisGoodsBuffer implements GoodsBuffer
{
    /**
     * Name of redis set
     */
    private const SET_NAME = 'index:goods';

    /**
     * @var int
     */
    private int $maxBatch;

    /**
     * @noinspection LaravelFunctionsInspection
     */
    public function __construct()
    {
        $this->maxBatch = (int)env("MAX_INDEXING_BATCH", 100);
    }

    /**
     * @inheritDoc
     * @noinspection PhpUndefinedMethodInspection
     */
    public function add(int $goodsId): void
    {
        Redis::sadd(self::SET_NAME, $goodsId);
    }

    /**
     * @inheritDoc
     * @noinspection PhpUndefinedMethodInspection
     */
    public function radd(array $goodsIds): void
    {
        Redis::sadd(self::SET_NAME, $goodsIds);
    }

    /**
     * @inheritDoc
     * @noinspection PhpUndefinedMethodInspection
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
