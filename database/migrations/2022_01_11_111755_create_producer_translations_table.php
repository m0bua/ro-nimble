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
    public function up(): void
    {
        if (Schema::hasTable('producer_translations')) {
            return;
        }

        Schema::create('producer_translations', static function (Blueprint $table) {
            $table->id();
            $table->bigInteger('producer_id')->nullable()->index();
            $table->string('lang', 3)->index();
            $table->string('column')->index();
            $table->text('value');
            $table->timestamp('created_at')->nullable()->useCurrent();
            $table->timestamp('updated_at')->nullable()->useCurrent();

            $table->unique(['producer_id', 'lang', 'column']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('producer_translations');
    }
}
