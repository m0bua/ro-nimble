<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePromotionGroupsConstructorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('promotion_groups_constructors', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('constructor_id');
            $table->bigInteger('group_id');
            $table->smallInteger('is_deleted')->default(0);
            $table->timestamp('created_at')->default('now()');
            $table->timestamp('updated_at')->default('now()');

            $table->index(['constructor_id', 'group_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('promotion_groups_constructors');
    }
}
