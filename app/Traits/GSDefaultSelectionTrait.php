<?php

namespace App\Traits;

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
            $this->query('producer')->setSelectionSet(['producer_id:id', 'producer_name:name', 'producer_title:title']),
            $this->query('rank')->setSelectionSet(['search_rank']),
            $this->query('options')->setSelectionSet([
                $this->query('details')->setSelectionSet(['id', 'name', 'type', 'state']),
                $this->inlineFragment('GoodsOptionSingle')->setSelectionSet(['value']),
                $this->inlineFragment('GoodsOptionPlural')->setSelectionSet([
                    $this->query('values')->setSelectionSet(['id', 'name', 'status']),
                ]),
            ]),
        ];
    }
}
