<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProducersAttachmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('producers_attachments', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('parent_id')->nullable();
            $table->bigInteger('producer_id')->nullable();
            $table->string('url')->nullable()->index();
            $table->integer('width')->nullable();
            $table->integer('height')->nullable();
            $table->string('variant')->nullable()->index();
            $table->string('group_name')->nullable()->index();
            $table->integer('order')->nullable();
            $table->boolean('is_deleted')->default(0);
            $table->smallInteger('need_delete')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('producers_attachments');
    }
}
