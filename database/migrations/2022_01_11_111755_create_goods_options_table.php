<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGoodsOptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        if (Schema::hasTable('goods_options')) {
            return;
        }

        Schema::create('goods_options', static function (Blueprint $table) {
            $table->bigInteger('id', true);
            $table->bigInteger('goods_id')->index();
            $table->bigInteger('option_id')->index();
            $table->string('type')->index('go_type_index');
            $table->text('value')->nullable();
            $table->integer('needs_index')->default(1)->index();
            $table->integer('is_deleted')->default(0);
            $table->timestamp('created_at')->nullable()->useCurrent();
            $table->timestamp('updated_at')->nullable()->useCurrent();

            $table->unique(['goods_id', 'option_id', 'type']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('goods_options');
    }
}
