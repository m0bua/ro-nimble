<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DeleteNeedsIndexPromotionConstructors extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('promotion_constructors', function (Blueprint $table) {
            $table->dropColumn('needs_index');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('promotion_constructors', function (Blueprint $table) {
            $table->smallInteger('needs_index')->default(1);
            $table->index('needs_index');
        });
    }
}
