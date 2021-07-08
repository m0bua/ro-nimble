<?php

namespace App\Processors\Traits;

use App\Cores\ConsumerCore\Interfaces\MessageInterface;
use App\Cores\Shared\Codes;
use Illuminate\Support\Arr;

/**
 * Process message and update entity
 *
 * Trait WithUpdate
 * @package App\Processors\Traits
 */
trait WithUpdate
{
    /**
     * @inheritDoc
     */
    public function processMessage(MessageInterface $message): int
    {
        $this->beforeProcess();

        $this->setDataFromMessage($message);
        $this->updateModel();

        $this->afterProcess();

        return Codes::SUCCESS;
    }

    /**
     * Update entity in DB
     *
     * @return bool
     */
    protected function updateModel(): bool
    {
        $data = $this->prepareData();
        $this->model
            ->when(
                static::$compoundKey,
                fn($q, $compoundKey) => $q->where(Arr::only($this->data, $compoundKey)),
                fn($q) => $q->where('id', $this->data['id'])
            )
            ->update($data);
        $this->saveTranslations();

        return true;
    }
}
