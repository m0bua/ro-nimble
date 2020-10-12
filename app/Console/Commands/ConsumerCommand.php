<?php

namespace App\Console\Commands;

use App\Cores\ConsumerCore\Consumer;
use App\Cores\ConsumerCore\Interfaces\MessageInterface;
use App\Cores\ConsumerCore\Loggers\ConsumerInfoLogger;
use App\Cores\ConsumerCore\Processor;
use App\Logging\ConsumerLogger;
use Illuminate\Console\Command;

class ConsumerCommand extends Command
{
    /**
     * @var string
     */
    protected $signature = 'consumer:start {config} {queue}';

    /**
     * @var string
     */
    protected $description = 'RabbitMQ consumer';

    /**
     * Handle command
     *
     * @throws \Exception
     */
    public function handle()
    {
        $queue = $this->argument('queue');
        $config = $this->argument('config');

        $consumer = new Consumer($config);
        $consumer->consume($queue, function (MessageInterface $message, string $iterationHash) use ($config, $queue) {
            $message->onError()->throwException();
            $processor = new Processor($message);

            ConsumerInfoLogger::log(
                $message->getRoutingKey(),
                $config,
                [
                    'hash' => $iterationHash,
                    'queue' => $queue,
                    'configuration' => $config,
                    'processor_name' => $processor->getName(),
                    'body' => $message->getBody(),
                ]
            );

            $processor->start();
        });
    }
}
