<?php

use App\Models\Eloquent\Goods;
use App\Support\Language;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MigrateGoodsTranslations extends Migration
{
    private array $translatable = [
        'title',
    ];

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Goods::chunkById(1000, function ($goods) {
            /** @var Goods $model */
            foreach ($goods as $model) {
                foreach ($this->translatable as $item) {
                    $value = $model->getRawOriginal($item);
                    if ($value === null) {
                        continue;
                    }

                    $model->$item = [
                        Language::RU => $value,
                    ];
                }
            }
        });

//        Schema::table('goods', function (Blueprint $table) {
//            $table->dropColumn($this->translatable);
//        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::table('goods', function (Blueprint $table) {
            foreach ($this->translatable as $item) {
                $table->string($item)->nullable();
            }
        });

        Goods::all()
            ->each(function (Goods $model) {
                foreach ($this->translatable as $item) {
                    $value = $model->getTranslation($item, Language::RU);
                    Goods::whereId($model->id)->update([
                        $item => $value,
                    ]);
                }

                $model->translations()->delete();
            });
    }
}
