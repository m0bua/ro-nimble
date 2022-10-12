<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DeleteIndexesFromFilterAutorankin extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('filters_autoranking', function (Blueprint $table) {
            $sm = Schema::getConnection()->getDoctrineSchemaManager();
            $doctrineTable = $sm->listTableDetails($table->getTable());

            if ($doctrineTable->hasIndex('filters_autoranking_tmp_filter_name_index')) {
                $table->dropIndex('filters_autoranking_tmp_filter_name_index');
            }
            if ($doctrineTable->hasIndex('filters_autoranking_tmp_parent_id_index')) {
                $table->dropIndex('filters_autoranking_tmp_parent_id_index');
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

    }
}
