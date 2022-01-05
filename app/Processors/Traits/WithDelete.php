<?php

namespace App\Processors\Traits;

use App\Cores\ConsumerCore\Interfaces\MessageInterface;
use App\Cores\Shared\Codes;
use Exception;
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
     * @throws Exception
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
     * @return void
     */
    protected function deleteModel(): void
    {
        $this->model
            ->when(
                static::$compoundKey,
                fn($q, $compoundKey) => $q->where(Arr::only($this->prepareData(), $compoundKey)),
                fn($q) => $q->where('id', $this->data['id'])
            )
            ->when(
                static::$softDelete ?? false,
                fn($q) => $q->update(self::updatableFields()),
                fn($q) => $q->delete(),
            );
    }

    public static function updatableFields(): array
    {
        return ['is_deleted' => 1];
    }
}
