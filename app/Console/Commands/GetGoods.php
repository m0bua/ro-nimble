<?php namespace App\Console\Commands;

use goods\graphQLBoroda\QueryBuilder\QueryBuilder;
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
        $model->selectFields(['id', 'name', 'title']);
//        $model->selectCategory(['id', 'title']);
//        $model->selectProducer(['id', 'title']);
//        $model->selectAttachments(['id', 'url']);
//        $model->selectTags(['title']);
//        $model->selectOptions(['option_id', 'details' => ['title'], 'value', 'values' => ['title'], 'type']);

        $result = $model->getById(200775625);


//        $model->selectFields(['name', 'title']);
//            ->selectCategory(['id', 'title'])
//            ->selectProducer(['id', 'title'])
//            ->selectAttachments(['id', 'url'])
//            ->selectOptions(['option_id', 'details' => ['title'], 'value', 'values' => ['title'], 'type']);


        $result = $model->getByIds([200775625, 200828737]);


        dump(
            $result, $model->getRemoteErrors()
        );
        die;
    }

}
