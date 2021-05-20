<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DeleteUnusedColumnsInOptionValuesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('option_values', function (Blueprint $table) {
            $table->dropColumn([
                'title_genetive',
                'title_accusative',
                'title_prepositional',
                'description',
                'similars_value',
            ]);
            $table->dateTime('created_at')->nullable()->default('CURRENT_TIMESTAMP')->change();
            $table->dateTime('updated_at')->nullable()->default('CURRENT_TIMESTAMP')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('option_values', function (Blueprint $table) {
            $table->string('similars_value')->nullable();
            $table->string('title_genetive')->nullable();
            $table->string('title_accusative')->nullable();
            $table->string('title_prepositional')->nullable();
            $table->string('description')->nullable();
            $table->dateTime('created_at')->default('now()')->change();
            $table->dateTime('updated_at')->default('now()')->change();
        });
    }
}
