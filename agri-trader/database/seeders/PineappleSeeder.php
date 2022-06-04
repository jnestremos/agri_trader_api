<?php

namespace Database\Seeders;

use App\Models\Produce;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PineappleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //         • Smooth Cayenne
        // • Queen Victoria
        // • Red Spanish
        // • Pernambuco

        $pineapples = ['Smooth Cayenne', 'Queen Victoria', 'Red Spanish', 'Pernambuco'];

        foreach ($pineapples as $pineapple) {
            Produce::create([
                'prod_name' => $pineapple,
                'prod_timeOfHarvest' => '480-540 days',
                'prod_type' => 'Pineapple'
            ]);
        }
    }
}
