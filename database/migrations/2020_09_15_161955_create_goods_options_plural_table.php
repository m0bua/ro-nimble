<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGoodsOptionsPluralTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('goods_options_plural', function (Blueprint $table) {
            $table->id();
            $table->integer('goods_id')->nullable();
            $table->integer('option_id')->nullable();
            $table->integer('value_id')->nullable();
            $table->integer('is_deleted')->default(0);
            $table->timestamp('created_at')->default('now()');
            $table->timestamp('updated_at')->default('now()');

            $table->index(['goods_id', 'option_id', 'value_id']);
            $table->index('goods_id');
            $table->index('option_id');
            $table->index('value_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('goods_options_plural');
    }
}
