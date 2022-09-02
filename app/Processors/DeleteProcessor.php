<?php

namespace App\Processors;

use App\Cores\ConsumerCore\Interfaces\MessageInterface;
use App\Cores\Shared\Codes;
use Illuminate\Support\Arr;

abstract class DeleteProcessor extends Processor
{
    /**
     * If true - entity will be marked as deleted only
     *
     * @var bool
     */
    protected bool $softDelete = false;

    /**
     * @inheritDoc
     */
    public function processMessage(MessageInterface $message): int
    {
        $this->beforeProcess();
        $this->setDataFromMessage($message);
        $this->deleteModel();
        $this->deleteTranslations();
        $this->afterProcess();

        return Codes::SUCCESS;
    }

    /**
     * Delete or mark entity as deleted
     *
     * @return void
     * @noinspection PhpPossiblePolymorphicInvocationInspection
     */
    protected function deleteModel(): void
    {
        $this->model
            ->when(
                !empty($this->compoundKey),
                fn($q) => $q->where(Arr::only($this->prepareData(), $this->compoundKey)),
                fn($q) => $q->where('id', $this->data['id'])
            )
            ->when(
                $this->softDelete ?? false,
                fn($q) => $q->update(self::updatableFields()),
                fn($q) => $q->delete(),
            );
    }

    /**
     * Delete entity translations after deleting model
     *
     * @return void
     * @noinspection PhpUndefinedMethodInspection
     */
    protected function deleteTranslations(): void
    {
        if (!$this->model->isRelation('translations')) {
            return;
        }

        $this->resolveEntity()
            ->translations()
            ->delete();
    }

    public static function updatableFields(): array
    {
        return [
            'is_deleted' => 1,
            'need_delete' => 0,
        ];
    }
}
