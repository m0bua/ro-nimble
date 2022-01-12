<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePromotionGroupsConstructorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        if (Schema::hasTable('promotion_groups_constructors')) {
            return;
        }

        Schema::create('promotion_groups_constructors', static function (Blueprint $table) {
            $table->bigInteger('id', true);
            $table->bigInteger('constructor_id');
            $table->bigInteger('group_id')->index();
            $table->smallInteger('needs_index')->default(1)->index();
            $table->smallInteger('is_deleted')->default(0)->index('idx_groups_constr_is_deleted');
            $table->timestamp('created_at')->nullable()->useCurrent();
            $table->timestamp('updated_at')->nullable()->useCurrent();

            $table->unique(['constructor_id', 'group_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('promotion_groups_constructors');
    }
}
