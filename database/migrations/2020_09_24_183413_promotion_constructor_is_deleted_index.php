<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class PromotionConstructorIsDeletedIndex extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('promotion_constructors', function (Blueprint $table) {
            $table->index('is_deleted', 'idx_promotion_constructors_is_deleted');
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
            $table->dropIndex('idx_promotion_constructors_is_deleted');
        });
    }
}
