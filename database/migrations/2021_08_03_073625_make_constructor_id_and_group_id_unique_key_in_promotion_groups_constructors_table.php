<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MakeConstructorIdAndGroupIdUniqueKeyInPromotionGroupsConstructorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('promotion_groups_constructors', function (Blueprint $table) {
            $table->unique([
                'constructor_id',
                'group_id',
            ]);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('promotion_groups_constructors', function (Blueprint $table) {
            $table->dropUnique([
                'constructor_id',
                'group_id',
            ]);
        });
    }
}
