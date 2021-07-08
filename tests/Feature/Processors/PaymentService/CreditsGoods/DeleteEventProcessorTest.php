<?php

namespace Tests\Feature\Processors\PaymentService\CreditsGoods;

use App\Models\Eloquent\Goods;
use App\Processors\PaymentService\CreditsGoods\DeleteEventProcessor;
use Exception;
use Illuminate\Contracts\Container\BindingResolutionException;
use Tests\Feature\Processors\ProcessorTestCase;

class DeleteEventProcessorTest extends ProcessorTestCase
{
    public static string $processorNamespace = DeleteEventProcessor::class;

    public static string $modelNamespace = Goods::class;

    public static ?string $dataRoot = null;

    public function testItWillSaveCreditMethodsForDefunctGoods(): void
    {
        $this->processor->processMessage($this->message);

        $this->assertMethodsMissing();
    }

    /**
     *
     * @noinspection PhpPossiblePolymorphicInvocationInspection
     * @noinspection PhpUnhandledExceptionInspection
     */
    public function testItWillSaveCreditMethodsForExistingGoods(): void
    {
        /** @var Goods $goods */
        $goods = $this->model->factory()->create();
        $this->setUpData($goods->id);
        $this->setUpMessage();
        $this->processor->processMessage($this->message);

        $this->assertMethodsMissing();
    }

    /**
     * @inheritDoc
     * @throws BindingResolutionException|Exception
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->setUpData();
        $this->setUpMessage();
    }

    /**
     * @param int|null $goodsId
     */
    private function setUpData(int $goodsId = null): void
    {
        $creditMethods = [];
        for ($i = 0; $i < $this->faker->randomDigitNotZero(); $i++) {
            $creditMethods[] = $this->faker->numberBetween(1, 10000);
        }

        $this->data = [
            'goods_id' => $goodsId ?? $this->faker->numberBetween(1, 10000),
            'credit_methods_for_goods' => $creditMethods,
        ];
    }

    /**
     * Check is methods exist in DB
     *
     * @noinspection PhpPossiblePolymorphicInvocationInspection
     */
    private function assertMethodsMissing(): void
    {
        $goodsId = $this->data['goods_id'];
        $table = $this->model->paymentMethods()->getTable();
        foreach ($this->data['credit_methods_for_goods'] as $methodId) {
            $this->assertDatabaseMissing($table, [
                'goods_id' => $goodsId,
                'payment_method_id' => $methodId,
            ]);
        }
    }
}
