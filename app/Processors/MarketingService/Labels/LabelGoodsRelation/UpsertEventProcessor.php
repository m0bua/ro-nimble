<?php

namespace App\Processors\MarketingService\Labels\LabelGoodsRelation;

use App\Cores\ConsumerCore\Interfaces\MessageInterface;
use App\Cores\Shared\Codes;
use App\Models\Eloquent\IndexGoods;
use App\Models\Eloquent\Label;
use App\Processors\Processor;
use Illuminate\Support\Facades\DB;

class UpsertEventProcessor extends Processor
{
    protected string $dataRoot = 'fields_data';

    private IndexGoods $indexGoods;

    /**
     * @param Label $model
     * @param IndexGoods $indexGoods
     */
    public function __construct(IndexGoods $indexGoods)
    {
        $this->indexGoods = $indexGoods;
    }

    /**
     * @inheritDoc
     */
    public function processMessage(MessageInterface $message): int
    {
        $this->beforeProcess();
        $this->setDataFromMessage($message);

        DB::table('goods_label')->insertOrIgnore($this->data);

        $this->afterProcess();

        return Codes::SUCCESS;
    }

    /**
     * @inheritDoc
     */
    protected function afterProcess(): void
    {
        $this->indexGoods->query()->insertOrIgnore(['id' => $this->data['goods_id']]);
    }
}
