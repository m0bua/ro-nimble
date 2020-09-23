<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('options', function (Blueprint $table) {
            $table->bigInteger('id');
            $table->string('title')->nullable();
            $table->string('name')->nullable();
            $table->string('type')->nullable();
            $table->string('ext_id')->nullable();
            $table->bigInteger('parent_id')->nullable();
            $table->bigInteger('category_id')->nullable();
            $table->string('filtering_type')->nullable();
            $table->string('value_separator')->nullable();
            $table->string('state')->nullable();
            $table->string('for_record_type')->nullable();
            $table->integer('order')->nullable();
            $table->integer('record_type')->nullable();
            $table->string('option_record_comparable')->nullable();
            $table->string('option_record_status')->nullable();
            $table->boolean('affect_group_photo')->nullable();
            $table->integer('is_deleted')->default(0);
            $table->timestamp('created_at')->default('now()');
            $table->timestamp('updated_at')->default('now()');

            $table->primary('id');
            $table->index('name');
            $table->index('parent_id');
            $table->index('category_id');
            $table->index('order');
            $table->index('record_type');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('options');
    }
}
