<?php

namespace App\Processors\Traits;

use App\Cores\ConsumerCore\Interfaces\MessageInterface;
use App\Cores\ConsumerCore\Loggers\ConsumerErrorLogger;
use App\Cores\Shared\Codes;
use BadMethodCallException;
use Exception;

/**
 * Process message and update or insert entity
 *
 * Trait WithUpsert
 * @package App\Processors\Traits
 */
trait WithUpsert
{
    /**
     * @inheritDoc
     * @throws Exception
     */
    public function processMessage(MessageInterface $message): int
    {
        $this->beforeProcess();
        $this->setDataFromMessage($message);

        if (!isset(static::$uniqueBy)) {
            throw new BadMethodCallException('Declare public static static::$uniqueBy before use upsert in [' . static::class . '] class');
        }

        $this->upsertModel(static::$uniqueBy);
        $this->afterProcess();

        return Codes::SUCCESS;
    }

    /**
     * Update or create entity
     *
     * @param array<string>|string $uniqueBy
     * @param array<string>|null $update
     * @return bool
     */
    protected function upsertModel($uniqueBy, ?array $update = null): bool
    {
        $data = $this->prepareData();
        $this->model->upsert($data, $uniqueBy, $update);

        // saving translations after creating record if we can do that
        $this->saveTranslations();

        return true;
    }
}
