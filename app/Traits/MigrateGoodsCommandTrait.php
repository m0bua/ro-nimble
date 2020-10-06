<?php
declare(strict_types=1);

namespace App\Traits;

use App\Helpers\CommonFormatter;

/**
 * Trait MigrateGoodsCommandTrait
 * @package App\Traits
 */
trait MigrateGoodsCommandTrait
{
    /**
     * @param array $nodes
     * @return array[]
     */
    public function formatGoodsNodes(array $nodes): array
    {
        $dataArray = [
            'goods' => [],
            'goods_options' => [],
            'goods_options_plural' => [],
            'options' => [],
            'option_values' => [],
            'producers' => [],
        ];

        foreach ($nodes as $productData) {
            $dataArray['goods'][] = CommonFormatter::t_Goods($productData);
            $producers = CommonFormatter::t_Producers($productData);
            if (!empty($producers)) {
                $dataArray['producers'][] = $producers;
            }
            $dataArray['options'] = array_merge($dataArray['options'], CommonFormatter::t_Options($productData));
            $dataArray['option_values'] = array_merge($dataArray['option_values'], CommonFormatter::t_OptionValues($productData));
            $dataArray['goods_options'] = array_merge($dataArray['goods_options'], CommonFormatter::t_GoodsOptions($productData));
            $dataArray['goods_options_plural'] = array_merge($dataArray['goods_options_plural'], CommonFormatter::t_GoodsOptionsPlural($productData));
        }

        return $dataArray;
    }
}
