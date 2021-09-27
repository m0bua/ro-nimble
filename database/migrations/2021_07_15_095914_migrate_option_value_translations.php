<?php

use App\Models\Eloquent\OptionValue;
use App\Support\Language;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MigrateOptionValueTranslations extends Migration
{
    private array $translatable = [
        'title',
        'title_genetive',
        'title_accusative',
        'title_prepositional',
        'description',
        'shortening',
    ];

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        /** @var OptionValue $optionValue */
        foreach (OptionValue::cursor() as $optionValue) {
            foreach ($this->translatable as $item) {
                $value = $optionValue->getRawOriginal($item);
                if ($value === null) {
                    continue;
                }

                $optionValue->$item = [
                    Language::RU => $value,
                ];
            }
        }

//        Schema::table('option_values', function (Blueprint $table) {
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
        Schema::table('option_values', function (Blueprint $table) {
            foreach ($this->translatable as $item) {
                $table->string($item)->nullable();
            }
        });

        OptionValue::all()
            ->each(function (OptionValue $model) {
                foreach ($this->translatable as $item) {
                    $value = $model->getTranslation($item, Language::RU);
                    OptionValue::whereId($model->id)->update([
                        $item => $value,
                    ]);
                }

                $model->translations()->delete();
            });
    }
}
