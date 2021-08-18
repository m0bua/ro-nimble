<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCategoryOptionTranslationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        if (Schema::hasTable('category_option_translations')) {
            return;
        }

        Schema::create('category_option_translations', static function (Blueprint $table) {
            $table->id();
            $table->bigInteger('category_option_id')->nullable();
            $table->string('lang', 3);
            $table->string('column');
            $table->text('value');
            $table->json('compound_key')->nullable();
            $table->timestamp('created_at')->nullable()->useCurrent();
            $table->timestamp('updated_at')->nullable()->useCurrent();

            $table->unique(['category_option_id', 'lang', 'column']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('category_option_translations');
    }
}
