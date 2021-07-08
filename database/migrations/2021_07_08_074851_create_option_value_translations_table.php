<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOptionValueTranslationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('option_value_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('option_value_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('lang', 3);
            $table->string('column');
            $table->text('value');
            $table->timestamps();

            $table->unique([
                'option_value_id',
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
        Schema::dropIfExists('option_value_translations');
    }
}
