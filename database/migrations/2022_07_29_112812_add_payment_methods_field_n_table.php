<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPaymentMethodsFieldNTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('payment_methods', function (Blueprint $table) {
            $table->bigInteger('payment_term_id')->nullable()->index();
        });
        if (Schema::hasTable('payment_methods_terms')) {
            return;
        }
        Schema::create('payment_methods_terms', static function (Blueprint $table) {
            $table->id();
            $table->string('title')->nullable()->index();
            $table->integer('number_of_payments')->nullable()->index();
            $table->integer('number_of_month')->nullable()->index();
            $table->float('min_goods_price_limit')->nullable();
            $table->float('max_goods_price_limit')->nullable();
            $table->timestamp('created_at')->nullable()->useCurrent();
            $table->timestamp('updated_at')->nullable()->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payment_methods_terms');
        Schema::table('payment_methods', function (Blueprint $table) {
            $table->dropColumn('payment_term_id');
        });
    }
}
