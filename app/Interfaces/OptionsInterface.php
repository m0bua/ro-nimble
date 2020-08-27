<?php

namespace App\Interfaces;

interface OptionsInterface
{
    public function get(): array;

    public function fill($data);
}
