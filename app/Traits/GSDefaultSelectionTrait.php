<?php

namespace App\Traits;

use App\Models\Eloquent\Goods;
use App\Models\Eloquent\Option;
use App\Models\Eloquent\Producer;

trait GSDefaultSelectionTrait
{
    /**
     * @return array|string[]
     */
    public function defaultSelectionSet(): array
    {
        return [
            'id',
            'name',
            'title',
            'category_id',
            'mpath',
            'price',
            'sell_status',
            'seller_id',
            'group_id',
            'is_group_primary',
            'status_inherited',
            'order',
            'series_id',
            'state',
            'pl_bonus_charge_pcs',
            $this->query('uk')->setSelectionSet(Goods::make()->getTranslatableProperties()),
            $this->query('producer')->setSelectionSet([
                'producer_id:id',
                'producer_name:name',
                'producer_title:title',
                $this->query('uk')->setSelectionSet(Producer::make()->getTranslatableProperties()),
            ]),
            $this->query('rank')->setSelectionSet(['search_rank']),
            $this->query('options')->setSelectionSet([
                $this->query('details')->setSelectionSet([
                    'id',
                    'name',
                    'type',
                    'state',
                    'title',
                    $this->query('uk')->setSelectionSet(Option::make()->getTranslatableProperties()),
                ]),
                $this->inlineFragment('GoodsOptionSingle')->setSelectionSet(['value', 'value_uk']),
                $this->inlineFragment('GoodsOptionPlural')->setSelectionSet([
                    $this->query('values')->setSelectionSet(['id', 'name', 'status']),
                ]),
            ]),
        ];
    }
}
