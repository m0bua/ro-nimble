<?php

namespace App\Processors\GoodsService;

use App\Cores\ConsumerCore\Interfaces\MessageInterface;
use App\Cores\ConsumerCore\Interfaces\ProcessorInterface;
use App\Cores\Shared\Codes;
use App\Models\Eloquent\Option;
use Illuminate\Support\Arr;

class ChangeOptionEntityProcessor implements ProcessorInterface
{
    /**
     * Eloquent model for updating data
     *
     * @var Option
     */
    protected Option $model;

    /**
     * ChangeOptionEntityProcessor constructor.
     * @param Option $model
     */
    public function __construct(Option $model)
    {
        $this->model = $model;
    }

    public function processMessage(MessageInterface $message): int
    {
        $fillable = $this->model->getFillable();
        $rawData = (array)$message->getField('data');
        $data = $this->prepareData(Arr::only($rawData, $fillable));

        $boolAttributes = [
            'affect_group_photo',
        ];

        foreach ($data as $key => &$datum) {
            if (in_array($key, $boolAttributes)) {
                $datum = $datum ? 'true' : 'false';
            }
        }

        $this->model
            ->write()
            ->where('id', $rawData['id'])
            ->update($data);

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
