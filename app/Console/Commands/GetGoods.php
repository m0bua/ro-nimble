<?php namespace App\Console\Commands;

use goods\graphqlmodels\models\Goods;

class GetGoods extends \Illuminate\Console\Command {
    protected $signature = 'goods:get';

    protected $description = '';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $model = new Goods();
        $model->selectFields(['name', 'title'])
            ->selectCategory(['id', 'title'])
            ->selectProducer(['id', 'title'])
            ->selectAttachments(['id', 'url'])
            ->selectOptions(['option_id', 'details' => ['title'], 'value', 'values' => ['title'], 'type']);

        dump(
            $model->getByIds([101108923, 83212569])
        );
        die;
    }

}
