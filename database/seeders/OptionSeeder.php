<?php

namespace Database\Seeders;

use App\Models\Eloquent\Option;
use App\Support\Language;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class OptionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $translatableProperties = Option::make()->getTranslatableProperties();
        Option::factory()
            ->count(10)
            ->create()
            ->each(static function (Option $option) use ($translatableProperties) {
                foreach ($translatableProperties as $property) {
                    $option->$property = [
                        Language::UK => Str::random(),
                        Language::RU => Str::random(),
                    ];
                }
            });
    }
}
