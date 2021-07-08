<?php

namespace Tests\Unit\Processors\AbstractProcessor;

use App\Cores\ConsumerCore\Interfaces\MessageInterface;
use App\Cores\Shared\Codes;
use App\Processors\AbstractProcessor;

class TestProcessor extends AbstractProcessor
{
    protected ?TestModel $model;

    /**
     * TestProcessor constructor.
     * @param TestModel|null $model
     */
    public function __construct(TestModel $model = null)
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
        static::$aliases = $aliases;
        return $this->prepareData();
    }

    public function _resolveField($field)
    {
        return $this->resolveField($field);
    }
}
