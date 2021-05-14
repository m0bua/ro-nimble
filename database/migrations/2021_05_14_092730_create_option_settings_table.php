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
    public function up()
    {
        Schema::create('option_settings', function (Blueprint $table) {
            $table->bigInteger('id');
            $table->bigInteger('category_id');
            $table->string('option_title');
            $table->string('option_id');
            $table->integer('order');
            $table->integer('print_order');
            $table->string('status');
            $table->boolean('in_short_description');
            $table->boolean('is_comparable');
            $table->boolean('show_selected_filter_title');
            $table->boolean('option_to_print');
            $table->boolean('is_searchable');
            $table->string('unit');
            $table->string('comment');
            $table->string('template');
            $table->string('comparable');
            $table->float('weight');
            $table->boolean('strict_equal_similars');
            $table->boolean('hide_block_in_filter');
            $table->string('more_word');
            $table->string('title_genetive');
            $table->string('title_accusative');
            $table->string('title_prepositional');
            $table->boolean('disallow_import_filters_orders');
            $table->string('number_template');
            $table->boolean('get_from_standard');
            $table->timestamps();

            $table->primary('id');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('option_settings');
    }
}
