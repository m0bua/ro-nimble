<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class SplitGoodsOptionsIntoTypes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('goods_option_booleans', function(Blueprint $table) {
            $table->id();
            $table->bigInteger('goods_id')->index();
            $table->bigInteger('option_id')->index();
            $table->timestamp('created_at')->nullable()->useCurrent();
            $table->timestamp('updated_at')->nullable()->useCurrent();

            $table->unique(['goods_id', 'option_id']);
        });

        Schema::create('goods_option_numbers', function(Blueprint $table) {
            $table->id();
            $table->bigInteger('goods_id')->index();
            $table->bigInteger('option_id')->index();
            $table->float('value');
            $table->timestamp('created_at')->nullable()->useCurrent();
            $table->timestamp('updated_at')->nullable()->useCurrent();

            $table->unique(['goods_id', 'option_id']);
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('goods_options_booleans');
        Schema::dropIfExists('goods_options_numbers');
    }
}
