<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProducersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('producers', function (Blueprint $table) {
            $table->bigInteger('id');
            $table->integer('order_for_promotion')->nullable();
            $table->integer('producer_rank')->nullable();
            $table->string('name')->nullable();
            $table->string('title')->nullable();
            $table->string('title_rus')->nullable();
            $table->string('ext_id')->nullable();
            $table->text('text')->nullable();
            $table->string('status')->nullable();
            $table->string('attachments')->nullable();

            $table->boolean('show_background')->nullable();
            $table->boolean('show_logo')->nullable();
            $table->boolean('disable_filter_series')->nullable();

            $table->integer('is_deleted')->default(0);
            $table->timestamp('created_at')->default('now()');
            $table->timestamp('updated_at')->default('now()');

            $table->primary('id');
            $table->index('name');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('producers');
    }
}
