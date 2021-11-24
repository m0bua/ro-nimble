<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSeriesTranslationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('series_translations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('series_id');
            $table->string('lang', 3);
            $table->string('column');
            $table->text('value');
            $table->timestamps();
            $table->unique([
                'series_id',
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
    public function down(): void
    {
        Schema::dropIfExists('series_translations');
    }
}
