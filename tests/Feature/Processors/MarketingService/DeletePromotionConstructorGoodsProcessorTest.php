<?php

namespace Tests\Feature\Processors\MarketingService;

use App\Models\Eloquent\PromotionGoodsConstructor;
use App\Processors\MarketingService\DeletePromotionConstructorGoodsProcessor;
use Tests\Feature\Processors\DeleteProcessorTestCase;

class DeletePromotionConstructorGoodsProcessorTest extends DeleteProcessorTestCase
{
    public static string $processorNamespace = DeletePromotionConstructorGoodsProcessor::class;

    public static string $modelNamespace = PromotionGoodsConstructor::class;

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
        $goodsId = $this->data[static::$dataRoot]['goods_id'];

        if (static::$hasSoftDeletes) {
            $this->assertEquals(
                1,
                $this->model
                    ->whereConstructorId($constructorId)
                    ->whereGoodsId($goodsId)
                    ->value('is_deleted')
            );
        } else {
            $this->assertDatabaseMissing(get_class($this->model), [
                'constructor_id' => $constructorId,
                'goods_id' => $goodsId,
            ]);
        }
    }

    /**
     * @inheritDoc
     * @noinspection PhpPossiblePolymorphicInvocationInspection
     */
    protected function setUpData(int $entityId): void
    {
        /** @var PromotionGoodsConstructor $entity */
        $entity = $this->model->find($entityId);

        if (!static::$dataRoot) {
            $this->data = [
                'id' => $entityId,
                'promotion_constructor_id' => $entity->constructor_id,
                'goods_id' => $entity->goods_id,
            ];
        } else {
            $this->data[static::$dataRoot] = [
                'id' => $entityId,
                'promotion_constructor_id' => $entity->constructor_id,
                'goods_id' => $entity->goods_id,
            ];
        }
    }
}
