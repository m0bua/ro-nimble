<?php


namespace App\ValueObjects;

use App\Processors\AbstractCore;

class RoutingKey
{
    /**
     * @var string
     */
    private $routingKey;

    /**
     * @var
     */
    private $processorClass;

    /**
     * RoutingKey constructor.
     * @param string $routingKey
     */
    public function __construct(string $routingKey)
    {
        $this->routingKey = $routingKey;
    }

    /**
     * @return string
     */
    public function get()
    {
        return $this->routingKey;
    }

    /**
     * @return AbstractCore
     */
    public function prepareProcessor(): AbstractCore
    {
        $this->generateProcessorName();

        return new $this->processorClass();
    }

    /**
     * TODO продумать над тем что бывынести эту генерацию в специфические классы
     * @return string
     */
    private function generateProcessorName()
    {
        $procName = ucfirst(
            str_replace(
                '_', '', str_replace(
                    '_record', '_Processor', str_replace(
                        '.', '_', $this->routingKey
                    )
                )
            )
        );

        $this->processorClass = "\App\Processors\\$procName";
    }
}
