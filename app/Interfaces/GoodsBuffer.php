<?php

namespace App\Interfaces;

use Generator;

interface GoodsBuffer
{
    /**
     * Add single goods ID to buffer
     *
     * @param int $goodsId
     * @return void
     */
    public function add(int $goodsId): void;

    /**
     * Add array of goods IDs to buffer
     *
     * @param array $goodsIds
     * @return void
     */
    public function radd(array $goodsIds): void;

    /**
     * Pop (fetch with delete from buffer) goods IDs from buffer by partitions
     *
     * @return Generator|int[]
     */
    public function scan(): Generator;
}
