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
    public function up()
    {
        Schema::create('promotion_constructors', function (Blueprint $table) {
            $table->bigInteger('id');
            $table->bigInteger('promotion_id');
            $table->bigInteger('gift_id')->nullable();
            $table->smallInteger('needs_index')->default(1);
            $table->smallInteger('is_deleted')->default(0);
            $table->timestamp('created_at')->default('now()');
            $table->timestamp('updated_at')->default('now()');

            $table->primary('id');
            $table->index('promotion_id');
            $table->index('needs_index');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('promotion_constructors');
    }
}
