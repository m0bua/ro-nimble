<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DeleteDuplicateIndexes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('goods_option_booleans', function (Blueprint $table) {
            $sm = Schema::getConnection()->getDoctrineSchemaManager();
            $doctrineTable = $sm->listTableDetails('goods_option_booleans');

            if ($doctrineTable->hasIndex('goods_option_booleans_goods_id_index')) {
                $table->dropIndex('goods_option_booleans_goods_id_index');
            }
        });

        Schema::table('goods_option_numbers', function (Blueprint $table) {
            $sm = Schema::getConnection()->getDoctrineSchemaManager();
            $doctrineTable = $sm->listTableDetails('goods_option_numbers');

            if ($doctrineTable->hasIndex('goods_option_numbers_goods_id_index')) {
                $table->dropIndex('goods_option_numbers_goods_id_index');
            }
        });

        Schema::table('goods_options', function (Blueprint $table) {
            $sm = Schema::getConnection()->getDoctrineSchemaManager();
            $doctrineTable = $sm->listTableDetails('goods_options');

            if ($doctrineTable->hasIndex('goods_options_goods_id_index')) {
                $table->dropIndex('goods_options_goods_id_index');
            }
        });

        Schema::table('goods_options_plural', function (Blueprint $table) {
            $sm = Schema::getConnection()->getDoctrineSchemaManager();
            $doctrineTable = $sm->listTableDetails('goods_options_plural');

            if ($doctrineTable->hasIndex('goods_options_plural_goods_id_index')) {
                $table->dropIndex('goods_options_plural_goods_id_index');
            }

            if ($doctrineTable->hasIndex('goods_options_plural_goods_id_option_id_value_id_index')) {
                $table->dropIndex('goods_options_plural_goods_id_option_id_value_id_index');
            }
        });

        Schema::table('promotion_goods_constructors', function (Blueprint $table) {
            $sm = Schema::getConnection()->getDoctrineSchemaManager();
            $doctrineTable = $sm->listTableDetails('promotion_goods_constructors');

            if ($doctrineTable->hasIndex('promotion_goods_constructors_constructor_id_goods_id_index')) {
                $table->dropIndex('promotion_goods_constructors_constructor_id_goods_id_index');
            }
        });

        Schema::table('options', function (Blueprint $table) {
            $sm = Schema::getConnection()->getDoctrineSchemaManager();
            $doctrineTable = $sm->listTableDetails('options');

            if ($doctrineTable->hasIndex('options_type_index')) {
                $table->dropIndex('options_type_index');
            }
        });

        Schema::table('promotion_groups_constructors', function (Blueprint $table) {
            $sm = Schema::getConnection()->getDoctrineSchemaManager();
            $doctrineTable = $sm->listTableDetails('promotion_groups_constructors');

            if ($doctrineTable->hasIndex('promotion_groups_constructors_constructor_id_group_id_index')) {
                $table->dropIndex('promotion_groups_constructors_constructor_id_group_id_index');
            }
        });
    }
}
