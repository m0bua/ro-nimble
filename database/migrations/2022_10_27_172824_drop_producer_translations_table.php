<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropProducerTranslationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('producer_translations');
        Schema::table('producers', function (Blueprint $table) {
            $table->dropColumn('title_rus');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (!Schema::hasTable('producer_translations')) {
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

        if (!Schema::hasColumn('producers', 'title_rus')) {
            Schema::table('producers', function (Blueprint $table) {
                $table->string('title_rus')->nullable();
            });
        }
    }
}
