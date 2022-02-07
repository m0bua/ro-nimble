<?php

namespace App\Processors\CommentService\GoodsComments;

use App\Models\Eloquent\GoodsComments;
use App\Processors\DeleteProcessor;

class DeleteCommentProcessor extends DeleteProcessor
{
    protected string $dataRoot = 'fields_data';
    protected array $aliases = ['id' => 'goods_id'];
    protected array $compoundKey = ['goods_id'];

    /**
     * @param GoodsComments $model
     */
    public function __construct(GoodsComments $model)
    {
        $this->model = $model;
    }
}
