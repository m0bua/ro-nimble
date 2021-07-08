<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUniqueKeyCategoryIdOptionIdInCategoryOptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('category_options', function (Blueprint $table) {
            $table->unique([
                'category_id',
                'option_id',
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
        Schema::table('category_options', function (Blueprint $table) {
            $table->dropUnique([
                'category_id',
                'option_id',
            ]);
        });
    }
}
