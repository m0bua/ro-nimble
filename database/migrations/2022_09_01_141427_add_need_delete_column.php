<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNeedDeleteColumn extends Migration
{
    /**
     * @var array
     */
    private array $tables = [
        'bonuses',
        'categories',
        'category_option_translations',
        'category_options',
        'category_translations',
        'goods',
        'goods_car_infos',
        'goods_comments',
        'goods_label',
        'goods_option_booleans',
        'goods_option_numbers',
        'goods_options',
        'goods_options_plural',
        'goods_payment_method',
        'goods_translations',
        'label_translations',
        'labels',
        'option_setting_translations',
        'option_settings',
        'option_translations',
        'option_value_category_relations',
        'option_value_relations',
        'option_value_translations',
        'option_values',
        'options',
        'payment_method_translations',
        'payment_methods',
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

    private string $columnName = 'need_delete';

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        foreach ($this->tables as $table) {
            if (!Schema::hasColumn($table, $this->columnName)) {
                Schema::table($table, function (Blueprint $table) {
                    $table->smallInteger($this->columnName)->default(0);
                });
            }
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        foreach ($this->tables as $table) {
            Schema::table($table, function (Blueprint $table) {
                $table->dropColumn($this->columnName);
            });
        }
    }
}
