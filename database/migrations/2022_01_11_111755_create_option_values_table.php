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
    public function up(): void
    {
        if (Schema::hasTable('option_values')) {
            return;
        }

        Schema::create('option_values', static function (Blueprint $table) {
            $table->bigInteger('id')->primary();
            $table->bigInteger('option_id')->nullable()->index();
            $table->bigInteger('parent_id')->nullable()->index();
            $table->string('ext_id')->nullable();
            $table->string('title')->nullable();
            $table->string('name')->nullable()->index();
            $table->string('status')->nullable()->index();
            $table->integer('order')->nullable();
            $table->string('similars_value')->nullable();
            $table->integer('show_value_in_short_set')->nullable();
            $table->string('color')->nullable();
            $table->string('title_genetive')->nullable();
            $table->string('title_accusative')->nullable();
            $table->string('title_prepositional')->nullable();
            $table->text('description')->nullable();
            $table->string('shortening')->nullable();
            $table->integer('record_type')->nullable();
            $table->integer('is_section')->nullable();
            $table->integer('is_deleted')->default(0);
            $table->timestamp('created_at')->nullable()->useCurrent();
            $table->timestamp('updated_at')->nullable()->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('option_values');
    }
}
