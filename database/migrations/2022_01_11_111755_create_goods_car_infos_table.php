<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGoodsCarInfosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        if (Schema::hasTable('goods_car_infos')) {
            return;
        }

        Schema::create('goods_car_infos', static function (Blueprint $table) {
            $table->id();
            $table->bigInteger('goods_id');
            $table->integer('car_trim_id');
            $table->integer('car_brand_id');
            $table->integer('car_model_id');
            $table->integer('car_year_id');
            $table->timestamp('created_at')->nullable()->useCurrent();
            $table->timestamp('updated_at')->nullable()->useCurrent();

            $table->unique(['goods_id', 'car_trim_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('goods_car_infos');
    }
}
