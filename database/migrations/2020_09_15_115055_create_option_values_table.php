<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOptionValuesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('option_values', function (Blueprint $table) {
            $table->bigInteger('id');
            $table->bigInteger('option_id')->nullable();
            $table->bigInteger('parent_id')->nullable();
            $table->string('ext_id')->nullable();
            $table->string('title')->nullable();
            $table->string('name')->nullable();
            $table->string('status')->nullable();
            $table->integer('order')->nullable();
            $table->string('similars_value')->nullable();
            $table->integer('show_value_in_short_set')->nullable();
            $table->string('color')->nullable();
            $table->string('title_genetive')->nullable();
            $table->string('title_accusative')->nullable();
            $table->string('title_prepositional')->nullable();
            $table->string('description')->nullable();
            $table->string('shortening')->nullable();
            $table->integer('record_type')->nullable();
            $table->integer('is_section')->nullable();
            $table->integer('is_deleted')->default(0);
            $table->timestamp('created_at')->default('now()');
            $table->timestamp('updated_at')->default('now()');

            $table->primary('id');
            $table->index('option_id');
            $table->index('parent_id');
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
        Schema::dropIfExists('option_values');
    }
}
