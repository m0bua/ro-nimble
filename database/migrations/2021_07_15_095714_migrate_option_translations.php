<?php

use App\Models\Eloquent\Option;
use App\Support\Language;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MigrateOptionTranslations extends Migration
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
//        Option::chunkById(1000, function ($options) {
//            /** @var Option $model */
//            foreach ($options as $model) {
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
//            }
//        });

//        Schema::table('options', function (Blueprint $table) {
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
        Schema::table('options', function (Blueprint $table) {
            foreach ($this->translatable as $item) {
                $table->string($item)->nullable();
            }
        });

        Option::all()
            ->each(function (Option $model) {
                foreach ($this->translatable as $item) {
                    $value = $model->getTranslation($item, Language::RU);
                    Option::whereId($model->id)->update([
                        $item => $value,
                    ]);
                }

                $model->translations()->delete();
            });
    }
}
