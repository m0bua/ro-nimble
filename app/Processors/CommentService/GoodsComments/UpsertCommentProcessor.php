<?php

namespace App\Processors\CommentService\GoodsComments;

use App\Models\Eloquent\GoodsComments;
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

    /**
     * @param GoodsComments $model
     */
    public function __construct(GoodsComments $model)
    {
        $this->model = $model;
    }
}
