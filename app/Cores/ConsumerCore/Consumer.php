<?php


namespace App\Cores\ConsumerCore;

use App\Cores\ConsumerCore\Loggers\ConsumerErrorLogger;
use App\Cores\Shared\Codes;
use Bschmitt\Amqp\Consumer as BaseConsumer;
use Closure;
use Exception;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Support\Facades\Log;
use Throwable;

class Consumer
{
    public const MAX_ERRORS_COUNT = 100;

    /**
     * Base consumer instance
     *
     * @var BaseConsumer
     */
    private BaseConsumer $consumer;

    /**
     * Config name
     *
     * @var string
     */
    private string $config;

    /**
     * Consumer constructor.
     * @param string $useConfig
     * @throws BindingResolutionException
     */
    public function __construct(string $useConfig)
    {
        config(['amqp.use' => $useConfig]);

        $this->config = $useConfig;
        $this->consumer = app()->make(BaseConsumer::class);
        $this->consumer->connect();
    }

    /**
     * Consume queue
     *
     * @param string $queue
     * @param Closure $callback
     * @param bool $stopWhenProcessed
     * @return void
     * @throws Exception
     */
    public function consume(string $queue, Closure $callback, bool $stopWhenProcessed = false): void
    {
        $errorsCount = 0;
        $this->consumer->consume($queue, function ($amqpMessage, BaseConsumer $resolver) use ($callback, &$errorsCount, $stopWhenProcessed) {
            $iterationHash = md5(random_bytes(10) . microtime());

            try {
                $message = new Message($amqpMessage);
                $resultCode = $callback($message, $iterationHash);

                if ($resultCode === Codes::SUCCESS) {
                    $resolver->acknowledge($amqpMessage);
                } else {
                    $resolver->reject($amqpMessage, $resultCode === Codes::SKIP);
                }

                if ($errorsCount > 0) {
                    $errorsCount = 0;
                }
            } catch (Throwable $t) {
                $resolver->reject($amqpMessage);
                $errorMessage = $t->getMessage();
                $data = [
                    'file' => $t->getFile(),
                    'line' => $t->getLine(),
                    'hash' => $iterationHash,
                    'configuration' => $this->config,
                    'consumer_got_message' => $amqpMessage->getBody(),
                    'routing_key' => $amqpMessage->delivery_info['routing_key'],
                ];

                ConsumerErrorLogger::log($errorMessage, $this->config, $data);
                Log::error($errorMessage, $data);

                // Missed server heartbeat
                if ($errorMessage === 'Missed server heartbeat') {
                    exit;
                }

                /** @noinspection LaravelFunctionsInspection */
                if ($errorsCount === (int)env('CONSUMER_MAX_ERRORS_COUNT', self::MAX_ERRORS_COUNT)) {
                    abort(500, $errorMessage);
                }

                $errorsCount++;
            }

            if ($stopWhenProcessed) {
                $resolver->stopWhenProcessed();
            }
        });
    }
}
