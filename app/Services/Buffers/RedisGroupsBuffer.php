<?php

namespace App\Services\Buffers;

use App\Interfaces\GroupsBuffer;
use Illuminate\Support\Facades\Redis;

class RedisGroupsBuffer implements GroupsBuffer
{
    /**
     * Name of redis set
     */
    private const SET_NAME = 'index:groups';

    /**
     * @noinspection LaravelFunctionsInspection
     */
    public function __construct() {}

    /**
     * @inerhitDoc
     * @noinspection PhpUndefinedMethodInspection
     */
    public function deleteGroups(): void
    {
        Redis::del(self::SET_NAME);
    }

    /**
     * @inerhitDoc
     * @noinspection PhpUndefinedMethodInspection
     */
    public function addProduct($groupId, $productId, $orders): void
    {
        Redis::hset(self::SET_NAME, "$groupId:$productId", \json_encode($orders));
    }

    /**
     * @inerhitDoc
     * @noinspection PhpUndefinedMethodInspection
     */
    public function getGroupOrder($groupId, $productId)
    {
        return Redis::hget(self::SET_NAME, "$groupId:$productId");
    }
}
