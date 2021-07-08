<?php

namespace Database\Seeders;

use App\Models\Eloquent\Producer;
use App\Support\Language;
use Faker\Generator;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Database\Seeder;

class ProducerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     * @throws BindingResolutionException
     */
    public function run()
    {
        $faker = app()->make(Generator::class);
        $translatableProperties = Producer::make()->getTranslatableProperties();
        Producer::factory()
            ->count(10)
            ->create()
            ->each(static function (Producer $producer) use ($faker, $translatableProperties) {
                foreach ($translatableProperties as $property) {
                    $producer->$property = [
                        Language::UK => $faker->word,
                        Language::RU => $faker->word,
                    ];
                }
            });
    }
}
