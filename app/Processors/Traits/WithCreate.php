<?php

namespace App\Processors\Traits;

use App\Cores\ConsumerCore\Interfaces\MessageInterface;
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
     * @inheritDoc
     */
    protected function prepareData(): array
    {
        $data = parent::prepareData();
        $data['created_at'] = now();
        $data['updated_at'] = $data['created_at'];

        return $data;
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
        $this->model->insertOrIgnore($data);

        // saving translations after creating record if we can do that
        $this->saveTranslations();

        return true;
    }
}
