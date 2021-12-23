<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIndexForUpsert extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('goods_options', function (Blueprint $table) {
            $table->unique(['goods_id', 'option_id', 'type']);
        });

        Schema::table('goods_options_plural', function (Blueprint $table) {
            $table->dropIndex(['goods_id', 'option_id', 'value_id']);
            $table->unique(['goods_id', 'option_id', 'value_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('goods_options', function (Blueprint $table) {
            $table->dropUnique(['goods_id', 'option_id', 'type']);
        });

        Schema::table('goods_options_plural', function (Blueprint $table) {
            $table->dropUnique(['goods_id', 'option_id', 'value_id']);
            $table->index(['goods_id', 'option_id', 'value_id']);
        });
    }
}
