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
    public function up(): void
    {
        if (Schema::hasTable('options')) {
            return;
        }

        Schema::create('options', static function (Blueprint $table) {
            $table->bigInteger('id')->primary();
            $table->string('title')->nullable();
            $table->string('name')->nullable()->index();
            $table->string('type')->nullable()->index('options_type_idx');
            $table->string('ext_id')->nullable();
            $table->bigInteger('parent_id')->nullable()->index();
            $table->bigInteger('category_id')->nullable()->index();
            $table->string('filtering_type')->nullable();
            $table->string('value_separator')->nullable();
            $table->string('state')->nullable()->index();
            $table->string('for_record_type')->nullable();
            $table->integer('order')->nullable()->index();
            $table->integer('record_type')->nullable()->index();
            $table->string('option_record_comparable')->nullable();
            $table->string('option_record_status')->nullable();
            $table->boolean('affect_group_photo')->nullable();
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
        Schema::dropIfExists('options');
    }
}
