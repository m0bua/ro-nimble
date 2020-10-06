<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsNeedsMigrate extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('promotion_goods_constructors', function (Blueprint $table) {
            $table->integer('needs_migrate')->default(1);
            $table->index('needs_migrate');
        });

        Schema::table('promotion_groups_constructors', function (Blueprint $table) {
            $table->integer('needs_migrate')->default(1);
            $table->index('needs_migrate');
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
            $table->dropIndex(['needs_migrate']);
            $table->dropColumn('needs_migrate');
        });

        Schema::table('promotion_goods_constructors', function (Blueprint $table) {
            $table->dropIndex(['needs_migrate']);
            $table->dropColumn('needs_migrate');
        });
    }
}
