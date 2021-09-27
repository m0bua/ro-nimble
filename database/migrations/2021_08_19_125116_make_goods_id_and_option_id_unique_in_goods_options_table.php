<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MakeGoodsIdAndOptionIdUniqueInGoodsOptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
//        Schema::table('goods_options', function (Blueprint $table) {
//            $table->unique([
//                'goods_id',
//                'option_id',
//                'type',
//            ]);
//        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('goods_options', function (Blueprint $table) {
            $table->dropUnique([
                'goods_id',
                'option_id',
                'type',
            ]);
        });
    }
}
