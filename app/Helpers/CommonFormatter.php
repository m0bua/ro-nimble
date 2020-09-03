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
     */
    public function __construct(array $incomeData)
    {
        $this->incomeData = $incomeData;
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
        $this->formattedData = array_merge($this->formattedData, $this->incomeData);

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
     * @return array
     */
    public function getFormattedData(): array
    {
        return $this->formattedData;
    }
}
