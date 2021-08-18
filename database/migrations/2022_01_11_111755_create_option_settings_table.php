<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOptionSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        if (Schema::hasTable('option_settings')) {
            return;
        }

        Schema::create('option_settings', static function (Blueprint $table) {
            $table->bigInteger('id')->primary();
            $table->bigInteger('category_id')->nullable();
            $table->bigInteger('option_id')->nullable();
            $table->integer('order')->nullable();
            $table->integer('print_order')->nullable();
            $table->string('status')->nullable();
            $table->boolean('in_short_description')->nullable();
            $table->boolean('is_comparable')->nullable();
            $table->boolean('show_selected_filter_title')->nullable();
            $table->boolean('option_to_print')->nullable();
            $table->boolean('is_searchable')->nullable();
            $table->string('unit')->nullable();
            $table->string('comment')->nullable();
            $table->string('template')->nullable();
            $table->string('comparable')->nullable();
            $table->float('weight')->nullable();
            $table->boolean('strict_equal_similars')->nullable();
            $table->boolean('hide_block_in_filter')->nullable();
            $table->string('special_combobox_view')->nullable();
            $table->string('more_word')->nullable();
            $table->boolean('disallow_import_filters_orders')->nullable();
            $table->string('number_template')->nullable();
            $table->boolean('get_from_standard')->nullable();
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
        Schema::dropIfExists('option_settings');
    }
}
