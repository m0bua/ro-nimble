<?php

namespace App\Processors\GoodsService;

use App\Cores\ConsumerCore\Interfaces\MessageInterface;
use App\Cores\ConsumerCore\Interfaces\ProcessorInterface;
use App\Cores\Shared\Codes;
use App\Models\Eloquent\OptionSetting;
use Exception;

class DeleteOptionSettingProcessor implements ProcessorInterface
{
    /**
     * Eloquent model for updating data
     *
     * @var OptionSetting
     */
    protected OptionSetting $model;

    /**
     * DeleteOptionSettingProcessor constructor.
     * @param OptionSetting $model
     */
    public function __construct(OptionSetting $model)
    {
        $this->model = $model;
    }

    /**
     * @param MessageInterface $message
     * @return int
     * @throws Exception
     */
    public function processMessage(MessageInterface $message): int
    {
        $id = $message->getField('id');

        $this->model->whereId($id)->delete();

        return Codes::SUCCESS;
    }
}
