<?php

namespace App\Processors\GoodsService;

use App\Cores\ConsumerCore\Interfaces\MessageInterface;
use App\Cores\ConsumerCore\Interfaces\ProcessorInterface;
use App\Cores\Shared\Codes;
use App\Models\Eloquent\OptionSetting;
use Illuminate\Support\Arr;

class ChangeOptionSettingProcessor implements ProcessorInterface
{
    /**
     * Eloquent model for updating data
     *
     * @var OptionSetting
     */
    protected OptionSetting $model;

    /**
     * ChangeOptionSettingProcessor constructor.
     * @param OptionSetting $model
     */
    public function __construct(OptionSetting $model)
    {
        $this->model = $model;
    }

    /**
     * @param MessageInterface $message
     * @return int
     */
    public function processMessage(MessageInterface $message): int
    {
        $id = $message->getField('data.id');

        $fillable = $this->model->getFillable();
        $rawData = (array)$message->getField('data');
        $data = $this->prepareData(Arr::only($rawData, $fillable));

        $this->model->where('id', $id)->update($data);

        return Codes::SUCCESS;
    }

    /**
     * Prepare data and cast booleans for PostgreSQL
     *
     * @param array $data
     * @return array
     */
    private function prepareData(array $data): array
    {
        $boolAttributes = $this->model->getBoolAttributes();

        foreach ($data as $key => &$datum) {
            if (in_array($key, $boolAttributes)) {
                $datum = $datum ? 'true' : 'false';
            }
        }

        return $data;
    }
}
