<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBonusesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::dropIfExists('bonuses');

        Schema::create('bonuses', static function (Blueprint $table) {
            $table->id();
            $table->bigInteger('goods_id')->unique();
            $table->integer('comment_bonus_charge');
            $table->integer('comment_photo_bonus_charge');
            $table->integer('comment_video_bonus_charge');
            $table->boolean('bonus_not_allowed_pcs');
            $table->integer('comment_video_child_bonus_charge');
            $table->integer('bonus_charge_pcs');
            $table->boolean('use_instant_bonus');
            $table->integer('premium_bonus_charge_pcs');
            $table->timestamp('created_at')->nullable()->useCurrent();
            $table->timestamp('updated_at')->nullable()->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('bonuses');
    }
}
