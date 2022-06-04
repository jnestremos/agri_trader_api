<?php

namespace Database\Seeders;

use App\Models\Produce;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BananaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $bananas = ['Cavendish', 'Saba', 'Lakatan'];

        foreach ($bananas as $banana) {
            Produce::create([
                'prod_name' => $banana,
                'prod_timeOfHarvest' => '420-480 days',
                'prod_type' => 'Banana'
            ]);
        }
    }
}
