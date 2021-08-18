<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGoodsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        if (Schema::hasTable('goods')) {
            return;
        }

        Schema::create('goods', static function (Blueprint $table) {
            $table->bigInteger('id')->primary();
            $table->string('title')->nullable();
            $table->string('name')->nullable()->index();
            $table->bigInteger('category_id')->nullable()->index();
            $table->string('mpath')->nullable();
            $table->float('price')->nullable();
            $table->float('rank')->nullable();
            $table->string('sell_status')->nullable()->index();
            $table->bigInteger('producer_id')->nullable()->index();
            $table->bigInteger('seller_id')->nullable()->index();
            $table->bigInteger('group_id')->nullable()->index();
            $table->bigInteger('is_group_primary')->nullable()->index();
            $table->string('status_inherited')->nullable();
            $table->bigInteger('order')->nullable()->index();
            $table->bigInteger('series_id')->nullable()->index();
            $table->string('state')->nullable();
            $table->integer('needs_index')->default(1)->index();
            $table->integer('is_deleted')->default(0)->index();
            $table->string('country_code')->nullable();
            $table->timestamp('created_at')->nullable()->useCurrent();
            $table->timestamp('updated_at')->nullable()->useCurrent();

            $table->index(['id', 'sell_status']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('goods');
    }
}
