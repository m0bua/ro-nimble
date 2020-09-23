<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGoodsOptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('goods_options', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('goods_id')->nullable();
            $table->bigInteger('option_id')->nullable();
            $table->string('type')->nullable();
            $table->text('value')->nullable();
            $table->integer('is_deleted')->default(0);
            $table->timestamp('created_at')->default('now()');
            $table->timestamp('updated_at')->default('now()');

            $table->index('goods_id');
            $table->index('option_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('goods_options');
    }
}
