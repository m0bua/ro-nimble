<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUniqueToSomeTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('goods_label', function (Blueprint $table) {
            $table->unique(['goods_id', 'label_id', 'country_code']);
        });

        Schema::table('goods_options_plural', function (Blueprint $table) {
            $sm = Schema::getConnection()->getDoctrineSchemaManager();
            $indexesFound = collect($sm->listTableIndexes('goods_options_plural'));
            $index = $indexesFound->get('goods_options_plural_goods_id_option_id_value_id_unique');
            if (!$index) {
                $table->unique(['goods_id', 'option_id', 'value_id']);
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
        Schema::table('some_tables', function (Blueprint $table) {
            //
        });
    }
}
