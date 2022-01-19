<?php

namespace Tests\Unit\Processors\Processor;

use App\Cores\ConsumerCore\Interfaces\MessageInterface;
use App\Cores\Shared\Codes;
use App\Processors\Processor;

class TestProcessor extends Processor
{
    /**
     * TestProcessor constructor.
     * @param TestModel|null $model
     */
    public function __construct(TestModel $model)
    {
        $this->model = $model;
    }

    /**
     * @inheritDoc
     */
    public function processMessage(MessageInterface $message): int
    {
        return Codes::SKIP;
    }

    public function _prepareData(array $data, array $aliases = []): array
    {
        $this->data = $data;
        $this->aliases = $aliases;
        return $this->prepareData();
    }

    public function _resolveField($field)
    {
        return $this->resolveField($field);
    }
}
