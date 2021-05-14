<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCategoryEntitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('category_entities', function (Blueprint $table) {
            $table->bigInteger('id');
            $table->string('mpath');
            $table->string('title');
            $table->string('status');
            $table->string('status_inherited');
            $table->integer('order');
            $table->integer('ext_id');
            $table->string('name');
            $table->string('titles_mode');
            $table->string('kits_show');
            $table->bigInteger('parent_id');
            $table->integer('left_key');
            $table->integer('right_key');
            $table->integer('level');
            $table->boolean('is_deleted');
            $table->string('sections_list');
            $table->string('href');
            $table->string('rz_mpath');
            $table->boolean('allow_index_three_parameters');
            $table->string('on_subdomain');
            $table->string('oversized');
            $table->boolean('is_subdomain');
            $table->boolean('disable_kit_ratio');
            $table->boolean('is_rozetka_top');
            $table->string('use_group_links');
            $table->boolean('show_comparison');
            $table->boolean('print_return_form');
            $table->boolean('returnless_goods');
            $table->softDeletes();
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
        Schema::dropIfExists('category_entities');
    }
}
