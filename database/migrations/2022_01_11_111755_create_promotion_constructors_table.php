<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePromotionConstructorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        if (Schema::hasTable('promotion_constructors')) {
            return;
        }

        Schema::create('promotion_constructors', static function (Blueprint $table) {
            $table->bigInteger('id')->primary();
            $table->bigInteger('promotion_id')->index();
            $table->bigInteger('gift_id')->nullable();
            $table->smallInteger('is_deleted')->default(0)->index('idx_promotion_constructors_is_deleted');
            $table->timestamp('created_at')->nullable()->useCurrent();
            $table->timestamp('updated_at')->nullable()->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('promotion_constructors');
    }
}
