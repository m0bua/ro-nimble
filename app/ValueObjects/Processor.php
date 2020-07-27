<?php


namespace App\ValueObjects;


use App\Processors\AbstractCore;

class Processor
{

    /**
     * @var Message
     */
    private $message;

    /**
     * @var AbstractCore
     */
    private $processorClass;

    public function __construct(Message $message)
    {
        $this->message = $message;
        $this->generateProcessorClass();
    }

    /**
     * @return mixed
     */
    public function run()
    {
        return (new $this->processorClass($this->message))->run();
    }

    /**
     * @throws \Exception
     */
    private function generateProcessorClass()
    {
        $use = config('amqp.use');
        $processorName = config("amqp.properties.$use.processor_name");

        if (is_callable($processorName)) {
            $this->processorClass = "\App\Processors\\{$processorName($this->message->getRoutingKey())}";
        } else {
            $this->processorClass = "\App\Processors\\{$processorName}";
        }
    }
}
