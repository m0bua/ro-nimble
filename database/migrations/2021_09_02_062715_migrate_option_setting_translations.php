<?php

use App\Models\Eloquent\OptionSetting;
use App\Support\Language;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MigrateOptionSettingTranslations extends Migration
{
    private array $translatable = [
        'unit',
        'more_word',
    ];

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        OptionSetting::all()
            ->each(function (OptionSetting $model) {
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

        Schema::table('option_settings', function (Blueprint $table) {
            $table->dropColumn($this->translatable);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::table('option_settings', function (Blueprint $table) {
            foreach ($this->translatable as $item) {
                $table->string($item)->nullable();
            }
        });

        OptionSetting::all()
            ->each(function (OptionSetting $model) {
                foreach ($this->translatable as $item) {
                    $value = $model->getTranslation($item, Language::RU);
                    OptionSetting::whereId($model->id)->update([
                        $item => $value,
                    ]);
                }

                optional($model->translations())->delete();
            });
    }
}
