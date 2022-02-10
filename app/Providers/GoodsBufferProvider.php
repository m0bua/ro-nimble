<?php

namespace App\Providers;

use App\Interfaces\GoodsBuffer;
use App\Services\Buffers\RedisGoodsBuffer;
use Illuminate\Support\ServiceProvider;

class GoodsBufferProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register(): void
    {
        $this->app->bind(GoodsBuffer::class, fn() => new RedisGoodsBuffer());
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot(): void
    {
        //
    }

    public function provides(): array
    {
        return [GoodsBuffer::class];
    }
}
