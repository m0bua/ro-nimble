<?php

namespace App\Console\Commands;

use App\Logging\CustomLogger;
use App\ValueObjects\Message;
use App\ValueObjects\Processor;
use Bschmitt\Amqp\Exception\Configuration;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use phpDocumentor\Reflection\Element;

class ConsumerCommand extends Command
{
    const MAX_ERRORS_COUNT = 100;

    /**
     * @var string
     */
    protected $signature = 'consumer:start {config} {queue}';

    /**
     * @var string
     */
    protected $description = 'RabbitMQ consumer';

    /**
     * @throws Configuration
     */
    public function handle()
    {
        config(['amqp.use' => $this->argument('config')]);

        $errorsCount = 0;

        $consumer = app()->make('Bschmitt\Amqp\Consumer');
        $consumer->connect();

        $consumer->consume($this->argument('queue'), function ($amqpMessage, $resolver) use (&$errorsCount) {
            try {
                if ($amqpMessage->body) {
                    $message   = new Message($amqpMessage);
                    $processor = new Processor($message);
                    $code = $processor->run();

                    if (in_array($code, [Processor::CODE_SUCCESS, Processor::CODE_SKIP])) {
                        $resolver->acknowledge($amqpMessage);

                        if ($errorsCount > 0) {
                            $errorsCount = 0;
                        }
                    }
                } else {
                    Log::channel('consumer')->warning('Message body is empty');
                    $resolver->acknowledge($amqpMessage);
                }

                unset($message, $processor);
            } catch (\Throwable $t) {
                $additionalLogData = ['configuration' => $this->argument('config')];
                if (isset($message)) {
                    $additionalLogData['consumer_got_message'] = $message->getBody();
                    $additionalLogData['routing_key'] = $message->getRoutingKey();
                }

                Log::channel('consumer')->error(
                    $errorMessage = CustomLogger::generateMessage($t, $additionalLogData)
                );

                if ($errorsCount == env('CONSUMER_MAX_ERRORS_COUNT', self::MAX_ERRORS_COUNT)) {
                    abort(500, $errorMessage);
                }

                $errorsCount++;
            }
        });
    }
}
