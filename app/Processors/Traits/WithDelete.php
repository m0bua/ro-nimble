<?php

namespace App\Processors\Traits;

use App\Cores\ConsumerCore\Interfaces\MessageInterface;
use App\Cores\Shared\Codes;
use Illuminate\Support\Arr;

/**
 * Process message and delete or mark as deleted entity
 *
 * Trait WithDelete
 * @package App\Processors\Traits
 */
trait WithDelete
{
    /**
     * @inheritDoc
     */
    public function processMessage(MessageInterface $message): int
    {
        $this->beforeProcess();

        $this->setDataFromMessage($message);
        $this->deleteModel();

        $this->afterProcess();

        return Codes::SUCCESS;
    }

    /**
     * Delete or mark entity as deleted
     *
     * @return int
     */
    protected function deleteModel(): int
    {
        return $this->model
            ->when(
                static::$compoundKey,
                fn($q, $compoundKey) => $q->where(Arr::only($this->data, $compoundKey)),
                fn($q) => $q->where('id', $this->data['id'])
            )
            ->when(
                static::$softDelete ?? false,
                fn($q) => $q->update(['is_deleted' => 1]),
                fn($q) => $q->delete(),
            );
    }
}
