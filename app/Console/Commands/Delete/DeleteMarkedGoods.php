<?php

namespace App\Console\Commands\Delete;

use App\Console\Commands\Command;
use App\Models\Elastic\GoodsModel;
use App\Models\Eloquent\Goods;
use Illuminate\Support\Collection;

class DeleteMarkedGoods extends Command
{
    /**
     * @var string
     */
    protected $signature = 'db:delete-marked-goods';

    /**
     * @var string
     */
    protected $description = 'Delete marked rows in DB';

    /**
     * @var GoodsModel
     */
    protected GoodsModel $elasticGoods;

    /**
     * @var Goods
     */
    protected Goods $model;

    protected const GOODS_COUNT_LIMIT = 500;

    /**
     * DeleteMarkedGoodsCommand constructor.
     * @param GoodsModel $elasticGoods
     * @param Goods $model
     */
    public function __construct(GoodsModel $elasticGoods, Goods $model)
    {
        $this->elasticGoods = $elasticGoods;
        $this->model = $model;

        parent::__construct();
    }

    /**
     * @inheritDoc
     */
    protected function proceed(): void
    {
        while (($deletedGoodsIds = $this->fetchDeletedGoodsIds()) && $deletedGoodsIds->isNotEmpty()) {
            $deletedGoodsIds->each(function (int $id) {
                $this->elasticGoods->delete([
                    'id' => $id
                ]);
            });

            $this->deleteGoodsFromDbByIds($deletedGoodsIds);
        }
    }

    /**
     * Get part of deleted goods from DB
     *
     * @return Collection
     */
    private function fetchDeletedGoodsIds(): Collection
    {
        return $this->model
            ->markedAsDeleted()
            ->limit(self::GOODS_COUNT_LIMIT)
            ->pluck('id');
    }

    /**
     * @param Collection $ids
     * @return void
     */
    private function deleteGoodsFromDbByIds(Collection $ids): void
    {
        $this->model->whereIn('id', $ids)->delete();
    }
}
