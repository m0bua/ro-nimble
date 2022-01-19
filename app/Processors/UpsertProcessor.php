<?php

namespace App\Processors;

use App\Cores\ConsumerCore\Interfaces\MessageInterface;
use App\Cores\Shared\Codes;

abstract class UpsertProcessor extends Processor
{
    /**
     * @inheritDoc
     */
    public function processMessage(MessageInterface $message): int
    {
        $this->beforeProcess();
        $this->setDataFromMessage($message);

        $uniqueBy = $this->compoundKey ?? ['id'];

        $this->upsertModel($uniqueBy);
        $this->afterProcess();

        return Codes::SUCCESS;
    }

    /**
     * Update or create entity
     *
     * @param array<string>|string $uniqueBy
     * @param array<string>|null $update
     * @return bool
     * @noinspection PhpPossiblePolymorphicInvocationInspection
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
