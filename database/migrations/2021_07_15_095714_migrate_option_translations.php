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
        /** @var Option $option */
        foreach (Option::cursor() as $option) {
            foreach ($this->translatable as $item) {
                $value = $option->getRawOriginal($item);
                if ($value === null) {
                    continue;
                }

                $option->$item = [
                    Language::RU => $value,
                ];
            }
        }

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
