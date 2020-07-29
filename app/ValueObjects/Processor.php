<?php


namespace App\ValueObjects;


use App\Processors\AbstractCore;
use Exception;

class Processor
{

    const CODE_SUCCESS = 0;
    const CODE_ERROR = 1;
    const CODE_SKIP = -1;

    /**
     * @var Message
     */
    private $message;

    /**
     * @var AbstractCore
     */
    private $processorClass;

    /**
     * Processor constructor.
     * @param Message $message
     * @throws Exception
     */
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
        if (class_exists($this->processorClass)) {
            return (new $this->processorClass($this->message))->run();
        }

        return Processor::CODE_SKIP;
    }

    /**
     * @throws Exception
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
