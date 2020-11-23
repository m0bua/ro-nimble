<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNewIndexes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('promotion_groups_constructors', function (Blueprint $table) {
            $table->index('group_id', 'promotion_groups_constructors_group_id_index');
        });

        Schema::table('options', function (Blueprint $table) {
            $table->index('type', 'options_type_index');
            $table->index('state', 'options_state_index');
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
            $table->dropIndex('promotion_groups_constructors_group_id_index');
        });

        Schema::table('options', function (Blueprint $table) {
            $table->dropIndex('options_type_index');
            $table->dropIndex('options_state_index');
        });
    }
}
