<?php

use App\Models\Eloquent\CategoryOption;
use App\Support\Language;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MigrateCategoryOptionTranslations extends Migration
{
    private array $translatable = [
        'value',
    ];

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
//        CategoryOption::all()
//            ->each(function (CategoryOption $model) {
//                foreach ($this->translatable as $item) {
//                    $value = $model->getRawOriginal($item);
//                    if ($value === null) {
//                        continue;
//                    }
//
//                    $model->$item = [
//                        Language::RU => $value,
//                    ];
//                }
//            });
//
//        Schema::table('category_options', function (Blueprint $table) {
//            $table->dropColumn($this->translatable);
//        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('category_options', function (Blueprint $table) {
            foreach ($this->translatable as $item) {
                $table->string($item)->nullable();
            }
        });

        CategoryOption::all()
            ->each(function (CategoryOption $model) {
                foreach ($this->translatable as $item) {
                    $value = $model->getTranslation($item, Language::RU);
                    CategoryOption::whereId($model->id)->update([
                        $item => $value,
                    ]);
                }

                $model->translations()->delete();
            });
    }
}
