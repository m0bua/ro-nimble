<?php

namespace App\Console\Commands;

use App\Logging\CustomLogger;
use App\ValueObjects\Message;
use App\ValueObjects\Processor;
use Bschmitt\Amqp\Amqp;
use Bschmitt\Amqp\Exception\Configuration;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

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
        (new Amqp())->consume($this->argument('queue'), function ($amqpMessage, $resolver) use (&$errorsCount) {
            try {
                $message   = new Message($amqpMessage);
                $processor = new Processor($message);
                $code = $processor->run();

                if (Processor::CODE_SUCCESS === $code) {
                    $resolver->acknowledge($amqpMessage);

                    if ($errorsCount > 0) {
                        $errorsCount = 0;
                    }
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

                if ($errorsCount == self::MAX_ERRORS_COUNT) {
                    abort(500, $errorMessage);
                }

                $errorsCount++;
            }
        });
    }
}
