<?php

use App\Models\Eloquent\Category;
use App\Support\Language;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MigrateCategoryTranslations extends Migration
{
    private array $translatable = [
        'title',
    ];

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Category::all()
            ->each(function (Category $model) {
                foreach ($this->translatable as $item) {
                    $value = $model->getRawOriginal($item);
                    if ($value === null) {
                        continue;
                    }

                    $model->$item = [
                        Language::RU => $value,
                    ];
                }
            });

        Schema::table('categories', function (Blueprint $table) {
            $table->dropColumn($this->translatable);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('categories', function (Blueprint $table) {
            foreach ($this->translatable as $item) {
                $table->string($item)->nullable();
            }
        });

        Category::all()
            ->each(function (Category $model) {
                foreach ($this->translatable as $item) {
                    $value = $model->getTranslation($item, Language::RU);
                    Category::whereId($model->id)->update([
                        $item => $value,
                    ]);
                }

                $model->translations()->delete();
            });
    }
}
