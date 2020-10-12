<?php


namespace App\Cores\ConsumerCore;
use App\Cores\ConsumerCore\Loggers\ConsumerErrorLogger;
use App\Logging\CustomLogger;
use Bschmitt\Amqp\Consumer as BaseConsumer;
use Illuminate\Support\Facades\Log;

class Consumer
{
    const MAX_ERRORS_COUNT = 100;

    /**
     * @var BaseConsumer
     */
    private BaseConsumer $consumer;

    /**
     * @var string
     */
    private string $config;

    /**
     * Consumer constructor.
     * @param string $useConfig
     */
    public function __construct(string $useConfig)
    {
        config(['amqp.use' => $useConfig]);

        $this->config = $useConfig;
        $this->consumer = app()->make('Bschmitt\Amqp\Consumer');
        $this->consumer->connect();
    }

    /**
     * @param string $queue
     * @param \Closure $callback
     * @throws \Exception
     */
    public function consume(string $queue, \Closure $callback)
    {
        $errorsCount = 0;
        $this->consumer->consume($queue, function ($amqpMessage, $resolver) use ($callback, &$errorsCount) {
            $iterationHash = md5(random_bytes(10) . microtime());
            $message = new Message($amqpMessage);

            try {
                $callback($message, $iterationHash);
                $resolver->acknowledge($amqpMessage);

                if ($errorsCount > 0) {
                    $errorsCount = 0;
                }
            } catch (\Throwable $t) {
                ConsumerErrorLogger::log($t->getMessage(), $this->config, [
                    'file' => $t->getFile(),
                    'line' => $t->getLine(),
                    'hash' => $iterationHash,
                    'configuration' => $this->config,
                    'consumer_got_message' => $message->getBody(),
                    'routing_key' => $message->getRoutingKey(),
                ]);

                if ($errorsCount == env('CONSUMER_MAX_ERRORS_COUNT', self::MAX_ERRORS_COUNT)) {
                    abort(500, $t->getMessage());
                }

                $errorsCount++;
            }
        });
    }
}
