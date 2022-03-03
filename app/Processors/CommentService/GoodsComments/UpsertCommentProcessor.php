<?php

namespace App\Processors\CommentService\GoodsComments;

use App\Interfaces\GoodsBuffer;
use App\Models\Eloquent\GoodsComment;
use App\Processors\UpsertProcessor;

class UpsertCommentProcessor extends UpsertProcessor
{
    protected string $dataRoot = 'fields_data';
    protected array $aliases = [
        'goods' => 'goods_id',
        'goodsGroupId' => 'goods_group_id',
        'countMarks' => 'count_marks',
        'summMarks' => 'summ_marks'
    ];
    protected array $compoundKey = ['goods_id'];

    private GoodsBuffer $goodsBuffer;

    /**
     * @param GoodsComment $model
     * @param GoodsBuffer $goodsBuffer
     */
    public function __construct(GoodsComment $model, GoodsBuffer $goodsBuffer)
    {
        $this->model = $model;
        $this->goodsBuffer = $goodsBuffer;
    }

    public function afterProcess(): void
    {
        $this->goodsBuffer->add($this->data['goods']);
    }
}
