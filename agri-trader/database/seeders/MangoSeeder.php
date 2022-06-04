<?php

namespace Database\Seeders;

use App\Models\Produce;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MangoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $mangoes = ['Carabao', 'Pico', 'Indian'];

        foreach ($mangoes as $mango) {
            Produce::create([
                'prod_name' => $mango,
                'prod_timeOfHarvest' => '120-140 days',
                'prod_type' => 'Mango'
            ]);
        }
    }
}
