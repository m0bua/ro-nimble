<?php

namespace App\Interfaces;

use Generator;

interface GoodsBufferInterface
{
    public function add(int $productId): void;

    public function radd(array $goodsIds): void;

    public function scan(): Generator;
}
