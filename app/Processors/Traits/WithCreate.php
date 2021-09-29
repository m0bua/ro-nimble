<?php

namespace App\Processors\Traits;

use App\Cores\ConsumerCore\Interfaces\MessageInterface;
use App\Cores\ConsumerCore\Loggers\ConsumerErrorLogger;
use App\Cores\Shared\Codes;
use Exception;

/**
 * Process message and create entity
 *
 * Trait WithCreate
 * @package App\Processors\Traits
 */
trait WithCreate
{
    /**
     * @inheritDoc
     * @throws Exception
     */
    public function processMessage(MessageInterface $message): int
    {
        $this->beforeProcess();
        $this->setDataFromMessage($message);
        $this->createModel();
        $this->afterProcess();

        return Codes::SUCCESS;
    }

    /**
     * Store entity in DB
     *
     * @return bool
     * @throws Exception
     */
    protected function createModel(): bool
    {
        $data = $this->prepareData();
        $this->model->create($data);

        // saving translations after creating record if we can do that
        $this->saveTranslations();

        return true;
//        return true;
    }
}
