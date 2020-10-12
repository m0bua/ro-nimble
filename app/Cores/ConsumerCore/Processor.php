<?php

namespace App\Cores\ConsumerCore;

use App\Cores\ConsumerCore\Interfaces\MessageInterface;
use App\Cores\ConsumerCore\Interfaces\ProcessorInterface;
use App\Processors\DefaultProcessor;

class Processor
{
    /**
     * @var ProcessorInterface
     */
    private ProcessorInterface $processor;

    /**
     * @var MessageInterface
     */
    private MessageInterface $message;

    /**
     * @var string
     */
    private string $processorName;

    /**
     * @var string
     */
    private string $processorClass;

    /**
     * Processor constructor.
     * @param MessageInterface $message
     */
    public function __construct(MessageInterface $message)
    {
        $use = config('amqp.use');
        $confProcName = config("amqp.properties.$use.processor_name");

        $this->message = $message;
        $this->processorName = is_callable($confProcName) ? $confProcName($this->message->getRoutingKey()) : $confProcName;
        $this->processorClass = "\App\Processors\\{$this->processorName}";
        $this->processor = (class_exists($this->processorClass)) ? new $this->processorClass() : new DefaultProcessor();
    }

    /**
     * Returns processor instance
     *
     * @return ProcessorInterface
     */
    public function get(): ProcessorInterface
    {
        return $this->processor;
    }

    /**
     * Returns processor name
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->processorName;
    }

    /**
     * Returns processor class
     *
     * @return string
     */
    public function getClass(): string
    {
        return $this->processorClass;
    }

    /**
     * Start processing message
     */
    public function start()
    {
        $this->processor->processMessage($this->message);
    }
}
