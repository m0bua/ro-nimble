<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->bigInteger('id');
            $table->integer('mpath')->nullable();
            $table->string('title')->nullable();
            $table->string('status')->nullable();
            $table->string('status_inherited')->nullable();
            $table->integer('order')->nullable();
            $table->integer('ext_id')->nullable();
            $table->string('name')->nullable();
            $table->string('titles_mode')->nullable();
            $table->string('kits_show')->nullable();
            $table->integer('parent_id')->nullable();
            $table->integer('left_key')->nullable();
            $table->integer('right_key')->nullable();
            $table->integer('level')->nullable();
            $table->string('sections_list')->nullable();
            $table->string('href')->nullable();
            $table->string('rz_mpath')->nullable();
            $table->boolean('allow_index_three_parameters')->nullable();
            $table->string('on_subdomain')->nullable();
            $table->string('oversized')->nullable();
            $table->boolean('is_subdomain')->nullable();
            $table->boolean('disable_kit_ratio')->nullable();
            $table->boolean('is_rozetka_top')->nullable();
            $table->integer('is_deleted')->default(0);
            $table->timestamp('created_at')->default('now()');
            $table->timestamp('updated_at')->default('now()');

            $table->primary('id');
            $table->index('name');
            $table->index('status');
            $table->index('is_deleted');
            $table->index('on_subdomain');
            $table->index('left_key');
            $table->index('right_key');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('categories');
    }
}
