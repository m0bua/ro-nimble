<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFiltersAutorankingTable extends Migration
{
    private string $table;

    /**
     * @param string $table
     */
    public function __construct(string $table = 'filters_autoranking')
    {
        $this->table = $table;
    }

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::dropIfExists($this->table);

        Schema::create($this->table, static function (Blueprint $table) {
            $table->id();
            $table->bigInteger('parent_id')->index();
            $table->string('filter_name')->index();
            $table->string('filter_value');
            $table->integer('filter_rank');
            $table->tinyInteger('is_value_show');
            $table->tinyInteger('is_filter_show');
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
        Schema::dropIfExists($this->table);
    }
}
