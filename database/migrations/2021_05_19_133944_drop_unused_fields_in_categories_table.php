<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropUnusedFieldsInCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->dropIndex(['on_subdomain']);

            $table->dropColumn([
                'title',
                'status',
                'status_inherited',
                'ext_id',
                'titles_mode',
                'kits_show',
                'sections_list',
                'href',
                'rz_mpath',
                'allow_index_three_parameters',
                'on_subdomain',
                'oversized',
                'is_subdomain',
                'disable_kit_ratio',
                'is_rozetka_top',
            ]);

            $table->index('level');
            $table->index('parent_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->string('title')->nullable();
            $table->string('status')->nullable();
            $table->string('status_inherited')->nullable();
            $table->integer('ext_id')->nullable();
            $table->string('titles_mode')->nullable();
            $table->string('kits_show')->nullable();
            $table->string('sections_list')->nullable();
            $table->string('href')->nullable();
            $table->string('rz_mpath')->nullable();
            $table->boolean('allow_index_three_parameters')->nullable();
            $table->string('on_subdomain')->nullable();
            $table->boolean('is_subdomain')->nullable();
            $table->string('oversized')->nullable();
            $table->boolean('disable_kit_ratio')->nullable();
            $table->boolean('is_rozetka_top')->nullable();

            $table->dropIndex(['level']);
            $table->dropIndex(['parent_id']);
        });
    }
}
