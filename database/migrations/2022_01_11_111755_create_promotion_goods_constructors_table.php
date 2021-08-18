<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePromotionGoodsConstructorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        if (Schema::hasTable('promotion_goods_constructors')) {
            return;
        }

        Schema::create('promotion_goods_constructors', static function (Blueprint $table) {
            $table->bigInteger('id', true);
            $table->bigInteger('constructor_id');
            $table->bigInteger('goods_id')->index();
            $table->smallInteger('needs_index')->default(1)->index();
            $table->smallInteger('is_deleted')->default(0)->index('idx_promotion_goods_constructor_is_deleted');
            $table->timestamp('created_at')->nullable()->useCurrent();
            $table->timestamp('updated_at')->nullable()->useCurrent();

            $table->index(['constructor_id', 'goods_id']);
            $table->unique(['constructor_id', 'goods_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('promotion_goods_constructors');
    }
}
