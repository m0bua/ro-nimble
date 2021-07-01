<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ReworkOfTimestampsInCategoriesTable extends Migration
{
    protected $connection = 'nimble';

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->dateTime('created_at')->nullable()->default('CURRENT_TIMESTAMP')->change();
            $table->dateTime('updated_at')->nullable()->default('CURRENT_TIMESTAMP')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->dateTime('created_at')->default('now()')->change();
            $table->dateTime('updated_at')->default('now()')->change();
        });
    }
}
