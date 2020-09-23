<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGoodsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('goods', function (Blueprint $table) {
            $table->bigInteger('id');
            $table->string('title')->nullable();
            $table->string('name')->nullable();
            $table->bigInteger('category_id')->nullable();
            $table->string('mpath')->nullable();
            $table->float('price')->nullable();
            $table->float('rank')->nullable();
            $table->string('sell_status')->nullable();
            $table->bigInteger('producer_id')->nullable();
            $table->bigInteger('seller_id')->nullable();
            $table->bigInteger('group_id')->nullable();
            $table->bigInteger('is_group_primary')->nullable();
            $table->string('status_inherited')->nullable();
            $table->bigInteger('order')->nullable();
            $table->bigInteger('series_id')->nullable();
            $table->string('state')->nullable();
            $table->integer('needs_index')->default(1);
            $table->integer('is_deleted')->default(0);
            $table->timestamp('created_at')->default('now()');
            $table->timestamp('updated_at')->default('now()');

            $table->primary('id');
            $table->index('category_id');
            $table->index('name');
            $table->index('producer_id');
            $table->index('seller_id');
            $table->index('group_id');
            $table->index('is_group_primary');
            $table->index('order');
            $table->index('series_id');
            $table->index('is_deleted');
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
        Schema::dropIfExists('goods');
    }
}
