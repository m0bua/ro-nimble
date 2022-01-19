<?php

namespace App\Processors;

use App\Cores\ConsumerCore\Interfaces\MessageInterface;
use App\Cores\Shared\Codes;
use Illuminate\Support\Arr;

abstract class UpdateProcessor extends Processor
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
     * @noinspection PhpPossiblePolymorphicInvocationInspection
     */
    protected function updateModel(): bool
    {
        $data = $this->prepareData();
        $this->model
            ->when(
                !empty($this->compoundKey),
                fn($q, $compoundKey) => $q->where(Arr::only($this->prepareData(), $compoundKey)),
                fn($q) => $q->where('id', $this->data['id'])
            )
            ->update($data);
        $this->saveTranslations();

        return true;
    }
}
