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
        try {
            $this->beforeProcess();
            $this->setDataFromMessage($message);
            $this->createModel();
            $this->afterProcess();
        } catch (Exception $e) {
            ConsumerErrorLogger::log($e->getMessage(), 'gs', [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'consumer_got_message' => $this->data,
            ]);
            throw $e;
        }

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
        try {
            $this->model->create($data);
        } catch (Exception $e) {
            $code = (int)$e->getCode();
            if ($code !== 23505) {
                throw $e;
            }

            return false;
        }

        // saving translations after creating record if we can do that
        $this->saveTranslations();

        return true;
    }
}
