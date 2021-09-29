<?php

namespace App\Console\Commands;

use App\Cores\ConsumerCore\Consumer;
use App\Cores\ConsumerCore\Interfaces\MessageInterface;
use App\Cores\ConsumerCore\Loggers\ConsumerErrorLogger;
use App\Cores\ConsumerCore\Loggers\ConsumerInfoLogger;
use App\Cores\ConsumerCore\Processor;
use Exception;
use Illuminate\Console\Command;

class StartConsumer extends Command
{
    /**
     * @var string
     */
    protected $signature = 'consumer:start {config} {queue}';

    /**
     * @var string
     */
    protected $description = 'Start RabbitMQ consumer';

    /**
     * Handle command
     *
     * @throws Exception
     */
    public function handle(): void
    {
        $queue = $this->argument('queue');
        $config = $this->argument('config');

        try {
            $consumer = new Consumer($config);
            $consumer->consume($queue, function (MessageInterface $message, string $iterationHash) use ($config, $queue) {
                $message->onError()->throwException();
                $processor = new Processor($message);
                $processorName = $processor->getName();

                $processor->processCallback(function () use ($message, $queue, $config, $iterationHash, $processorName) {
                    ConsumerInfoLogger::log(
                        $message->getRoutingKey(),
                        $config,
                        [
                            'hash' => $iterationHash,
                            'queue' => $queue,
                            'configuration' => $config,
                            'processor_name' => $processorName,
                            'body' => $message->getBody(),
                        ]
                    );
                });

                $processor->start();
            });
        } catch (Exception $e) {
            ConsumerErrorLogger::log($e->getMessage(), $config, [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'configuration' => $config,
            ]);
        }
    }
}
