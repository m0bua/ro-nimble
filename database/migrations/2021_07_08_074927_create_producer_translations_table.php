<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProducerTranslationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('producer_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('producer_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('lang', 3);
            $table->string('column');
            $table->text('value');
            $table->timestamps();

            $table->unique([
                'producer_id',
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
        Schema::dropIfExists('producer_translations');
    }
}
