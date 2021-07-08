<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MakeConstructorIdAndGoodsIdUniqueKeyInPromotionGoodsConstructorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('promotion_goods_constructors', function (Blueprint $table) {
            $table->unique([
                'constructor_id',
                'goods_id',
            ]);
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
            $table->dropUnique([
                'constructor_id',
                'goods_id',
            ]);
        });
    }
}
