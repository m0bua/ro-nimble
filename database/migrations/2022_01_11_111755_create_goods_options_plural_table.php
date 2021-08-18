<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGoodsOptionsPluralTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        if (Schema::hasTable('goods_options_plural')) {
            Schema::table('goods_options_plural', static function (Blueprint $table) {
                $table->bigInteger('goods_id')->change();
            });

            return;
        }

        Schema::create('goods_options_plural', static function (Blueprint $table) {
            $table->id();
            $table->bigInteger('goods_id')->index();
            $table->integer('option_id')->index();
            $table->integer('value_id')->index();
            $table->integer('needs_index')->default(1)->index();
            $table->integer('is_deleted')->default(0);
            $table->timestamp('created_at')->nullable()->useCurrent();
            $table->timestamp('updated_at')->nullable()->useCurrent();

            $table->unique(['goods_id', 'option_id', 'value_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('goods_options_plural');
    }
}
