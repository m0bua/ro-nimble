<?php


namespace App\Interfaces;


interface MessageInterface
{
    public function getBody(): object;

    public function getRoutingKey(): string;

    public function getField(string $fieldRoute);
}
