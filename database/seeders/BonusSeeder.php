<?php

namespace Database\Seeders;

use App\Models\Eloquent\Bonus;
use Illuminate\Database\Seeder;

class BonusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        Bonus::factory()->count(10000)->create();
    }
}
