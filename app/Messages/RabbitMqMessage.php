<?php

namespace App\Messages;

abstract class RabbitMqMessage implements Queueable
{
    /**
     * Service data
     *
     * @var array
     */
    protected array $serviceData = [];

    /**
     * Set service data
     *
     * @param array $serviceData
     */
    public function setServiceData(array $serviceData): void
    {
        $this->serviceData = $serviceData;
    }
}
