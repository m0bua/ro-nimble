<?php

namespace App\Traits\Eloquent;

trait HasWriteDb
{
    /**
     * Returns a new instance with local connection
     *
     * @return static
     * @noinspection PhpMissingReturnTypeInspection
     */
    public function write()
    {
        $writeDbName = config('database.write');
        // First we will just create a fresh instance of this model, and then we can set the
        // connection on the model so that it is used for the queries we execute, as well
        // as being set on every relation we retrieve without a custom connection name.
        $instance = new static();

        $instance->setConnection($writeDbName);

        return $instance;
    }
}
