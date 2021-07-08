<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOptionSettingTranslationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('option_setting_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('option_setting_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('lang', 3);
            $table->string('column');
            $table->text('value');
            $table->timestamps();
            $table->unique([
                'option_setting_id',
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
        Schema::dropIfExists('option_setting_translations');
    }
}
