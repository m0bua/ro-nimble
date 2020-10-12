<?php


namespace App\Cores\ConsumerCore\Interfaces;


interface MessageInterface
{
    public function getBody(): object;

    public function getRoutingKey(): string;

    public function getField(string $fieldRoute);
}
