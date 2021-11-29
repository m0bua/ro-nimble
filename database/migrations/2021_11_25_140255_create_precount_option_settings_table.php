<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePrecountOptionSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('precount_option_settings', function (Blueprint $table) {
            $table->bigInteger('option_id')->unsigned();
            $table->bigInteger('category_id')->unsigned();
            $table->bigInteger('options_settings_id')->unsigned();
            $table->smallInteger('is_deleted')->default(0);

            $table->unique(['option_id', 'category_id']);
            $table->index('is_deleted');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('precount_option_settings');
    }
}
