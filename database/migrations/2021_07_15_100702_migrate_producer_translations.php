<?php

use App\Models\Eloquent\Producer;
use App\Support\Language;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MigrateProducerTranslations extends Migration
{
    private array $translatable = [
        'title',
        'title_rus',
    ];

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        /** @var Producer $producer */
        foreach (Producer::cursor() as $producer) {
            $name = $producer->getRawOriginal('name');
            $title = $producer->getRawOriginal('title');

            $producer->title = [
                Language::RU => $title,
            ];

            $producer->name = [
                Language::RU => $name,
            ];
        }

//        Schema::table('producers', function (Blueprint $table) {
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
        Schema::table('producers', function (Blueprint $table) {
            foreach ($this->translatable as $item) {
                $table->string($item)->nullable();
            }
        });

        Producer::all()
            ->each(function (Producer $model) {
                $value = $model->getTranslations('title');
                Producer::whereId($model->id)->update([
                    'title' => $value[Language::RU] ?? null,
                    'name' => $model->getTranslation('name', Language::RU),
                ]);

                $model->translations()->delete();
            });
    }
}
