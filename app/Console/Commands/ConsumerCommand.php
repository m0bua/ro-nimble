<?php

namespace App\Console\Commands;

use App\ValueObjects\Message;
use App\ValueObjects\Processor;
use Bschmitt\Amqp\Amqp;
use Bschmitt\Amqp\Exception\Configuration;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

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
     * @throws Configuration
     */
    public function handle()
    {
        config(['amqp.use' => $this->argument('config')]);

        (new Amqp())->consume($this->argument('queue'), function ($amqpMessage, $resolver) {
            try {
                $message   = new Message($amqpMessage);
                $processor = new Processor($message);
                $code = $processor->run();

                if (Processor::CODE_SUCCESS === $code) {
//                    $resolver->acknowledge($amqpMessage);
                }
            } catch (\Throwable $t) {
                Log::error("{$t->getMessage()}; File: {$t->getFile()}; Line: {$t->getLine()}");
//                abort(500, "{$t->getMessage()}; File: {$t->getFile()}; Line: {$t->getLine()}");
            }
        });
    }
}
