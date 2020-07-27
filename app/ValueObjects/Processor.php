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
     * TODO продумать над тем что бывынести эту генерацию в специфические классы
     * @return string
     */
    private function generateProcessorClass()
    {
        $procName = ucfirst(
            str_replace(
                '_', '', str_replace(
                    '_record', '_Processor', str_replace(
                        '.', '_', $this->message->getRoutingKey()
                    )
                )
            )
        );

        $this->processorClass = "\App\Processors\\$procName";
    }
}
