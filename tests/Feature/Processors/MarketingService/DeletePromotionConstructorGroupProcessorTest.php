<?php

namespace Tests\Feature\Processors\MarketingService;

use App\Models\Eloquent\PromotionGroupConstructor;
use App\Processors\MarketingService\DeletePromotionConstructorGroupProcessor;
use Tests\Feature\Processors\DeleteProcessorTestCase;

class DeletePromotionConstructorGroupProcessorTest extends DeleteProcessorTestCase
{
    public static string $processorNamespace = DeletePromotionConstructorGroupProcessor::class;

    public static string $modelNamespace = PromotionGroupConstructor::class;

    public static bool $hasOwnId = false;

    public static bool $hasSoftDeletes = true;

    public static ?string $dataRoot = 'fields_data';

    /**
     * @inheritDoc
     * @noinspection PhpPossiblePolymorphicInvocationInspection
     */
    protected function assertDeletedEntity(): void
    {
        $constructorId = $this->data[static::$dataRoot]['promotion_constructor_id'];
        $groupId = $this->data[static::$dataRoot]['group_id'];

        if (static::$hasSoftDeletes) {
            $this->assertEquals(
                1,
                $this->model
                    ->whereConstructorId($constructorId)
                    ->whereGroupId($groupId)
                    ->value('is_deleted')
            );
        } else {
            $this->assertDatabaseMissing(get_class($this->model), [
                'constructor_id' => $constructorId,
                'group_id' => $groupId,
            ]);
        }
    }

    /**
     * @inheritDoc
     * @noinspection PhpPossiblePolymorphicInvocationInspection
     */
    protected function setUpData(int $entityId): void
    {
        /** @var PromotionGroupConstructor $entity */
        $entity = $this->model->find($entityId);

        if (!static::$dataRoot) {
            $this->data = [
                'id' => $entityId,
                'promotion_constructor_id' => $entity->constructor_id,
                'group_id' => $entity->group_id,
            ];
        } else {
            $this->data[static::$dataRoot] = [
                'id' => $entityId,
                'promotion_constructor_id' => $entity->constructor_id,
                'group_id' => $entity->group_id,
            ];
        }
    }
}
