<?php

namespace App\Processors\CommentService\GoodsComments;

use App\Interfaces\GoodsBuffer;
use App\Models\Eloquent\GoodsComments;
use App\Processors\DeleteProcessor;

class DeleteCommentProcessor extends DeleteProcessor
{
    protected string $dataRoot = 'fields_data';
    protected array $aliases = ['id' => 'goods_id'];
    protected array $compoundKey = ['goods_id'];
    private GoodsBuffer $goodsBuffer;
    /**
     * @param GoodsComments $model
     * @param GoodsBuffer $goodsBuffer
     */
    public function __construct(GoodsComments $model, GoodsBuffer $goodsBuffer)
    {
        $this->model = $model;
        $this->goodsBuffer = $goodsBuffer;
    }

    public function afterProcess(): void
    {
        $this->goodsBuffer->add($this->data['goods_id']);
    }
}
