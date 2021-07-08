<?php


namespace App\Helpers;

use App\ValueObjects\Options;

class CommonFormatter
{

    private array $incomeData;

    private array $formattedData = [];

    /**
     * ResponseHelper constructor.
     * @param $incomeData
     *
     * @deprecated $incomeData in constructor will remove in future
     */
    public function __construct(array $incomeData = [])
    {
        $this->incomeData = $incomeData;
    }

    /**
     * @param array $data
     */
    public function setIncomeData(array $data)
    {
        $this->incomeData = $data;
    }

    /**
     * Преобразует данные о товаре к правильному виду
     *
     * @return $this
     */
    public function formatGoodsForIndex(): CommonFormatter
    {
        if (isset($this->incomeData['mpath'])) {
            $this->formattedData['categories_path'] = array_map(
                'intval',
                array_values(
                    array_filter(
                        explode('.', $this->incomeData['mpath'])
                    )
                )
            );
        }

        if (isset($this->incomeData['seller_id'])) {
            $this->formattedData['seller_order'] = $this->incomeData['seller_id'] == 5 ? 1 : 0;
        }

        if (isset($this->incomeData['tags'])) {
            $this->formattedData['tags'] = array_column($this->incomeData['tags'], 'id');
        }

        if (isset($this->incomeData['producer'])) {
            $this->formattedData = array_merge($this->formattedData, $this->incomeData['producer']);
        }

        if (isset($this->incomeData['rank'])) {
            if (is_array($this->incomeData['rank'])) {
                $this->formattedData = array_merge($this->formattedData, $this->incomeData['rank']);
            } else {
                $this->formattedData['search_rank'] = $this->incomeData['rank'];
            }
        }

        unset($this->incomeData['producer'], $this->incomeData['rank'], $this->incomeData['mpath']);
        $this->formattedData = array_merge($this->incomeData, $this->formattedData);

        return $this;
    }

    /**
     * @return $this
     */
    public function formatOptionsForIndex(): CommonFormatter
    {
        $options = new Options();
        $options->fill($this->incomeData['options']);
        unset($this->incomeData['options']);
        $this->formattedData = array_merge($this->formattedData, $options->get());

        return $this;
    }

    /**
     * @param array $productData
     * @return array
     */
    public static function t_Goods(array $productData): array
    {
        $specData = [];
        $specData['needs_index'] = 1;
        $specData['producer_id'] = 0;
        $specData['rank'] = null;

        if (!empty($productData['producer'])) {
            $specData['producer_id'] = $productData['producer']['producer_id'];
        }
        if (!empty($productData['rank'])) {
            $specData['rank'] = $productData['rank']['search_rank'];
        }

        unset(
            $productData['producer'],
            $productData['options'],
            $productData['tags'],
            $productData['pl_bonus_charge_pcs']
        );

        return array_merge($productData, $specData);
    }

    /**
     * @param array $productData
     * @return array
     */
    public static function t_Producers(array $productData): array
    {
        $producerData = [];
        if (!empty($productData['producer'])) {
            $producerData = [
                'id' => $productData['producer']['producer_id'],
                'name' => $productData['producer']['producer_name'],
                'title' => $productData['producer']['producer_title'],
                'uk' => [
                    'title' => $productData['producer']['uk']['title'] ?? '',
                ],
            ];
        }

        return $producerData;
    }

    /**
     * @param array $productData
     * @return array
     */
    public static function t_Options(array $productData): array
    {
        $optRecords = [];
        if (!empty($productData['options'])) {
            $options = $productData['options'];
            unset($productData);

            foreach ($options as $option) {
                if (!empty($option['details']) && !empty($option['details']['id'])) {
                    $optRecords[$option['details']['id']] = $option['details'];
                }
            }
        }

        return $optRecords;
    }

    /**
     * @param array $productData
     * @return array
     */
    public static function t_OptionValues(array $productData): array
    {
        $valRecords = [];
        if (!empty($productData['options'])) {
            $options = $productData['options'];
            unset($productData);

            foreach ($options as $option) {
                if (!empty($option['values'])) {
                    foreach ($option['values'] as $value) {
                        $valRecords[$value['id']] = $value;
                    }
                }
            }
        }

        return $valRecords;
    }

    /**
     * @param array $productData
     * @return array
     */
    public static function t_GoodsOptions(array $productData): array
    {
        $goodsOptRecords = [];
        if (!empty($productData['options'])) {
            $goodsId = $productData['id'];
            $options = $productData['options'];
            unset($productData);

            foreach ($options as $option) {
                if (isset($option['values'])) {
                    continue;
                }

                if (!empty($option['details'])) {
                    $goodsOptRecords[] = [
                        'goods_id' => $goodsId,
                        'option_id' => $option['details']['id'],
                        'type' => $option['details']['type'],
                        'value' => $option['value'],
                        'uk' => [
                            'value' => $option['value_uk'] ?? ''
                        ],
                    ];
                }
            }
        }

        return $goodsOptRecords;
    }

    /**
     * @param array $productData
     * @return array
     */
    public static function t_GoodsOptionsPlural(array $productData): array
    {
        $goodsOptPluralRecord = [];
        if (!empty($productData['options'])) {
            $goodsId = $productData['id'];
            $options = $productData['options'];
            unset($productData);

            foreach ($options as $option) {
                if (!empty($option['values']) && !empty($option['details'])) {
                    foreach ($option['values'] as $value) {
                        $goodsOptPluralRecord[] = [
                            'goods_id' => $goodsId,
                            'option_id' => $option['details']['id'],
                            'value_id' => $value['id'],
                        ];
                    }

                }
            }
        }

        return $goodsOptPluralRecord;
    }

    /**
     * @param $groups
     * @return $this
     */
    public function formatGroupsForIndex($groups)
    {
        if (!empty($this->formattedData['group_id']) && !empty($groups[$this->formattedData['group_id']])) {
            $this->formattedData['promotion_constructors'] = array_merge(
                $this->formattedData['promotion_constructors'],
                $groups[$this->formattedData['group_id']]
            );
        }

        return $this;
    }

    /**
     * @return array
     */
    public function getFormattedData(): array
    {
        return $this->formattedData;
    }
}
