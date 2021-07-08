<?php

namespace App\Traits\Eloquent;

trait HasDynamicBinds
{
    /**
     * Change model table and connection in runtime
     *
     * @param string|null $table
     * @param string|null $connection
     * @return $this
     */
    public function bind(string $table = null, string $connection = null): self
    {
        if ($table) {
            $this->setTable($table);
        }

        if ($connection) {
            $this->setConnection($connection);
        }

        return $this;
    }
}
