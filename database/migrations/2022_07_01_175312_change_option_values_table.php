<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeOptionValuesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('option_values', static function (Blueprint $table) {
            $table->dropColumn('title_genetive');
            $table->dropColumn('title_accusative');
            $table->dropColumn('title_prepositional');
            $table->dropColumn('description');
            $table->dropColumn('shortening');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('option_values', static function (Blueprint $table) {
            $table->string('title_genetive')->nullable();
            $table->string('title_accusative')->nullable();
            $table->string('title_prepositional')->nullable();
            $table->text('description')->nullable();
            $table->string('shortening')->nullable();
        });
    }
}
