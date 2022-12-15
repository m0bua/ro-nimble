<?php

namespace App\Providers;

use App\Interfaces\GoodsBuffer;
use App\Interfaces\GroupsBuffer;
use App\Services\Buffers\RedisGoodsBuffer;
use App\Services\Buffers\RedisGroupsBuffer;
use Illuminate\Support\ServiceProvider;

class GroupsBufferProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register(): void
    {
        $this->app->bind(GroupsBuffer::class, fn() => new RedisGroupsBuffer());
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
        return [GroupsBuffer::class];
    }
}
