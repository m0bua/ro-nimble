<?php


namespace App\Interfaces;


interface ElasticInterface
{
    /**
     * Определяет название индекса
     *
     * @return string
     */
    public function indexName(): string;

    /**
     * Указывает обязательные поля для заполнения.
     * Возвращает пустой массив, если таковых не имеется
     *
     * @return array
     */
    public function requiredFields(): array;
}
