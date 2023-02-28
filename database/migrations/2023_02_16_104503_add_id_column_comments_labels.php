<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIdColumnCommentsLabels extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('goods_label', function (Blueprint $table) {
            $table->id();
        });
        Schema::table('goods_comments', function (Blueprint $table) {
            $table->dropPrimary('goods_comments_pkey');
            $table->unique('goods_id', 'goods_comments_goods_id_unique');
        });
        Schema::table('goods_comments', function (Blueprint $table) {
            $table->id();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('goods_label', function (Blueprint $table) {
            $table->dropColumn(['id']);
        });
        Schema::table('goods_comments', function (Blueprint $table) {
            $table->dropColumn(['id']);
        });
        Schema::table('goods_comments', function (Blueprint $table) {
            $table->dropUnique('goods_comments_goods_id_unique');
            $table->primary(['goods_id'], 'goods_comments_pkey');
        });
    }
}
