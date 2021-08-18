<?php

namespace App\Console\Commands;

use App\Cores\ConsumerCore\Consumer;
use App\Cores\ConsumerCore\Interfaces\MessageInterface;
use App\Cores\ConsumerCore\Loggers\ConsumerInfoLogger;
use App\Cores\ConsumerCore\Processor;
use Exception;
use Illuminate\Console\Command;

class StartConsumer extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'consumer:start {config} {queue} {--stopWhenProcessed}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Start RabbitMQ consumer';

    /**
     * Execute the console command.
     *
     * @return int
     * @throws Exception
     */
    public function handle(): int
    {
        $queue = (string)$this->argument('queue');
        $config = (string)$this->argument('config');
        $stopWhenProcessed = (bool)$this->option('stopWhenProcessed');

        $consumer = new Consumer($config);

        $consumer->consume($queue, function (MessageInterface $message, string $iterationHash) use ($config, $queue) {
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

            return $processor->start();
        }, $stopWhenProcessed);

        return 0;
    }
}
