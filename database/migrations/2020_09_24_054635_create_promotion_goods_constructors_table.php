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
    public function up()
    {
        Schema::create('promotion_goods_constructors', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('constructor_id');
            $table->bigInteger('goods_id');
            $table->smallInteger('needs_index')->default(1);
            $table->smallInteger('is_deleted')->default(0);
            $table->timestamp('created_at')->default('now()');
            $table->timestamp('updated_at')->default('now()');

            $table->index(['constructor_id', 'goods_id']);
            $table->index('needs_index');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('promotion_goods_constructors');
    }
}
