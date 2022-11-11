<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNeedDeleteIndex extends Migration
{
    private static $tables = [
        'category_option_translations',
        'goods_car_infos',
        'category_options',
        'labels',
        'goods_label',
        'goods_option_booleans',
        'goods_option_numbers',
        'payment_method_translations',
        'goods_translations',
        'option_settings',
        'option_value_relations',
        'option_value_translations',
        'options',
        'payment_methods',
        'bonuses',
        'goods',
        'goods_comments',
        'goods_options',
        'goods_options_plural',
        'category_translations',
        'goods_payment_method',
        'label_translations',
        'categories',
        'option_setting_translations',
        'option_translations',
        'option_value_category_relations',
        'option_values',
        'payment_methods_terms',
        'payment_parent_method_translations',
        'payment_parent_methods',
        'producer_translations',
        'producers',
        'promotion_constructors',
        'promotion_goods_constructors',
        'promotion_groups_constructors',
        'series',
        'series_translations',
    ];

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        foreach (self::$tables as $tableName) {
            Schema::table($tableName, function (Blueprint $table) use ($tableName) {
                $table->index(['need_delete'], $tableName . "_need_delete_index");
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        foreach (self::$tables as $tableName) {
            Schema::table($tableName, function (Blueprint $table) use ($tableName) {
                $table->dropIndex($tableName . "_need_delete_index");
            });
        }
    }
}
