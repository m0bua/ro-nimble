<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGoodsPaymentMethodTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        if (Schema::hasTable('goods_payment_method')) {
            return;
        }

        Schema::create('goods_payment_method', static function (Blueprint $table) {
            $table->id();
            $table->bigInteger('goods_id')->index();
            $table->bigInteger('payment_method_id')->index();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('goods_payment_method');
    }
}
