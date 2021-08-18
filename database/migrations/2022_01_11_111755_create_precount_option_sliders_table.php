<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePrecountOptionSlidersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        if (Schema::hasTable('precount_option_sliders')) {
            return;
        }

        Schema::create('precount_option_sliders', static function (Blueprint $table) {
            $table->bigInteger('option_id');
            $table->bigInteger('category_id');
            $table->float('max_value');
            $table->float('min_value');
            $table->string('comparable', 32);
            $table->smallInteger('is_deleted')->default(0)->index();

            $table->unique(['option_id', 'category_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('precount_option_sliders');
    }
}
