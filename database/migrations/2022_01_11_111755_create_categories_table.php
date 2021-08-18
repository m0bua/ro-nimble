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
    public function up(): void
    {
        if (Schema::hasTable('categories')) {
            return;
        }

        Schema::create('categories', static function (Blueprint $table) {
            $table->bigInteger('id')->primary();
            $table->string('mpath')->nullable();
            $table->string('title')->nullable();
            $table->string('status')->nullable()->index();
            $table->string('status_inherited')->nullable();
            $table->integer('order')->nullable();
            $table->string('ext_id')->nullable();
            $table->string('name')->nullable()->index();
            $table->string('titles_mode')->nullable();
            $table->string('kits_show')->nullable();
            $table->integer('parent_id')->nullable();
            $table->integer('left_key')->nullable()->index();
            $table->integer('right_key')->nullable()->index();
            $table->integer('level')->nullable();
            $table->string('sections_list')->nullable();
            $table->string('href')->nullable();
            $table->string('rz_mpath')->nullable();
            $table->boolean('allow_index_three_parameters')->nullable();
            $table->string('on_subdomain')->nullable()->index();
            $table->string('oversized')->nullable();
            $table->boolean('is_subdomain')->nullable();
            $table->boolean('disable_kit_ratio')->nullable();
            $table->boolean('is_rozetka_top')->nullable();
            $table->integer('is_deleted')->default(0)->index();
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
        Schema::dropIfExists('categories');
    }
}
