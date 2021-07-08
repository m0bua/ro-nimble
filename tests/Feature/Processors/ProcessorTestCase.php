<?php

namespace Tests\Feature\Processors;

use App\Cores\ConsumerCore\Interfaces\MessageInterface;
use App\Cores\ConsumerCore\Interfaces\ProcessorInterface;
use App\Cores\ConsumerCore\Message;
use Exception;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use PhpAmqpLib\Message\AMQPMessage;
use Tests\TestCase;

abstract class ProcessorTestCase extends TestCase
{
    use WithFaker;
    use RefreshDatabase;

    /**
     * Processors namespace for automatic test set up
     *
     * @var string
     */
    public static string $processorNamespace;

    /**
     * Models namespace for automatic test set up
     *
     * @var string
     */
    public static string $modelNamespace;

    /**
     * Aliases for data preparing
     * Needs if field's name in message doesn't equal to one in DB
     *
     * @var array
     */
    public static array $aliases = [];

    /**
     * Determines is processed entity has own ID from third party service
     *
     * @var bool
     */
    public static bool $hasOwnId = true;

    /**
     * Root key in data array, if null data won't be wrapped
     *
     * @var string|null
     */
    public static ?string $dataRoot = 'data';

    /**
     * Processor for test
     *
     * @var ProcessorInterface
     */
    protected ProcessorInterface $processor;

    /**
     * Processors entity
     *
     * @var Model
     */
    protected Model $model;

    /**
     * Message for processor
     *
     * @var MessageInterface
     */
    protected MessageInterface $message;

    /**
     * Data for AMQ Message
     *
     * @var array
     */
    protected array $data;

    /**
     * Expected saved data
     *
     * @var array
     */
    protected array $expected;

    protected bool $withNeedsIndex = false;
    protected bool $withNeedsMigrate = false;

    /**
     * Setup the test environment.
     *
     * @return void
     * @throws BindingResolutionException|Exception
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->setUpAggregatedProperties();
    }

    /**
     * Return entity ID
     *
     * @return int
     * @noinspection PhpPossiblePolymorphicInvocationInspection
     */
    protected function getEntityId(): int
    {
        if (static::$hasOwnId) {
            return static::$dataRoot ? $this->data[static::$dataRoot]['id'] : $this->data['id'];
        }

        return $this->model->max('id');
    }

    /**
     * Setup processor, model and translation
     *
     * @return void
     * @throws BindingResolutionException
     */
    protected function setUpAggregatedProperties(): void
    {
        $this->processor = $this->app->make(static::$processorNamespace);
        $this->model = $this->app->make(static::$modelNamespace);
    }

    /**
     * Setup the AMQ Message
     *
     * @return void
     * @throws Exception
     */
    protected function setUpMessage(): void
    {
        $amqpMessage = new AMQPMessage(json_encode($this->data, JSON_THROW_ON_ERROR));
        $this->message = new Message($amqpMessage);
    }
}
