<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRegionalTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('category_regionals', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('category_id');
            $table->string('country', 3)->index();
            $table->string('column')->index();
            $table->text('value');
            $table->smallInteger('need_delete')->default(0);
            $table->timestamps();
            $table->unique(['category_id', 'country', 'column']);
            $table->index(['need_delete'], "category_regionals_need_delete_index");
        });
        Schema::create('option_regionals', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('option_id');
            $table->string('country', 3)->index();
            $table->string('column')->index();
            $table->text('value');
            $table->smallInteger('need_delete')->default(0);
            $table->timestamps();
            $table->unique(['option_id', 'country', 'column']);
            $table->index(['need_delete'], "option_regionals_need_delete_index");
        });
        Schema::create('option_value_regionals', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('option_value_id');
            $table->string('country', 3)->index();
            $table->string('column')->index();
            $table->text('value');
            $table->smallInteger('need_delete')->default(0);
            $table->timestamps();
            $table->unique(['option_value_id', 'country', 'column']);
            $table->index(['need_delete'], "option_value_regionals_need_delete_index");
        });
        Schema::create('series_regionals', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('series_id');
            $table->string('country', 3)->index();
            $table->string('column')->index();
            $table->text('value');
            $table->smallInteger('need_delete')->default(0);
            $table->timestamps();
            $table->unique(['series_id', 'country', 'column']);
            $table->index(['need_delete'], "series_regionals_need_delete_index");
        });
        Schema::create('producer_regionals', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('producer_id');
            $table->string('country', 3)->index();
            $table->string('column')->index();
            $table->text('value');
            $table->smallInteger('need_delete')->default(0);
            $table->timestamps();
            $table->unique(['producer_id', 'country', 'column']);
            $table->index(['need_delete'], "producer_regionals_need_delete_index");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('category_regionals');
        Schema::dropIfExists('option_regionals');
        Schema::dropIfExists('option_value_regionals');
        Schema::dropIfExists('option_setting_regionals');
        Schema::dropIfExists('series_regionals');
        Schema::dropIfExists('producer_regionals');
    }
}
