<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveForeignsInTranslations extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {

        $tables = [
            'category_translations' => 'category_id',
            'category_option_translations' => 'category_option_id',
            'goods_translations' => 'goods_id',
            'option_translations' => 'option_id',
            'option_value_translations' => 'option_value_id',
            'producer_translations' => 'producer_id',
            'option_setting_translations' => 'option_setting_id',
        ];

        foreach ($tables as $table => $column) {
            Schema::table($table, function (Blueprint $table) use ($column) {
                $table->dropForeign([$column]);
            });
        }

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        //
    }
}
