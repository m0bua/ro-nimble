<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddedIndexes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('option_settings', function (Blueprint $table) {
            $table->index('status');
            $table->index('order');
        });

        Schema::table('option_values', function (Blueprint $table) {
            $sm = Schema::getConnection()->getDoctrineSchemaManager();
            $indexesFound = $sm->listTableIndexes('option_values');

            if (!array_key_exists("option_values_status_index", $indexesFound)) {
                $table->index('status');
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('option_settings', function (Blueprint $table) {
            $table->dropIndex(['order']);
            $table->dropIndex(['status']);
        });

        Schema::table('option_values', function (Blueprint $table) {
            $table->dropIndex(['status']);
        });
    }
}
