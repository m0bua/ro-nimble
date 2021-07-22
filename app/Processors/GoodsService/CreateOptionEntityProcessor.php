<?php

namespace App\Processors\GoodsService;

use App\Cores\ConsumerCore\Interfaces\MessageInterface;
use App\Cores\ConsumerCore\Interfaces\ProcessorInterface;
use App\Cores\Shared\Codes;
use App\Models\Eloquent\Option;

class CreateOptionEntityProcessor implements ProcessorInterface
{
    /**
     * Eloquent model for updating data
     *
     * @var Option
     */
    protected Option $model;

    /**
     * CreateOptionEntityProcessor constructor.
     * @param Option $model
     */
    public function __construct(Option $model)
    {
        $this->model = $model;
    }

    public function processMessage(MessageInterface $message): int
    {
        $data = (array)$message->getField('data');

        $this->model

            ->insertOrIgnore([
                'id' => $data['id'],
                'title' => $data['title'],
                'name' => $data['name'],
                'type' => $data['type'],
                'ext_id' => $data['ext_id'],
                'parent_id' => $data['parent_id'],
                'category_id' => $data['category_id'],
                'filtering_type' => $data['filtering_type'],
                'value_separator' => $data['value_separator'],
                'state' => $data['state'],
                'for_record_type' => $data['for_record_type'],
                'order' => $data['order'],
                'record_type' => $data['record_type'],
                'option_record_comparable' => $data['option_record_comparable'],
                'option_record_status' => $data['option_record_status'],
                'affect_group_photo' => ($data['affect_group_photo']) ? 't' : 'f',
            ]);

        return Codes::SUCCESS;
    }
}
