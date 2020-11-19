<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIndexPromotionGoodsConstructorsGoodsId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('promotion_goods_constructors', function (Blueprint $table) {
            $table->index('goods_id', 'promotion_goods_constructors_goods_id_index');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('promotion_goods_constructors', function (Blueprint $table) {
            $table->dropIndex('promotion_goods_constructors_goods_id_index');
        });
    }
}
