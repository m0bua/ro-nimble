<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGoodsTranslationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('goods_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('goods_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('lang', 3);
            $table->string('column');
            $table->text('value');
            $table->timestamps();

            $table->unique([
                'goods_id',
                'lang',
                'column',
            ]);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('goods_translations');
    }
}
