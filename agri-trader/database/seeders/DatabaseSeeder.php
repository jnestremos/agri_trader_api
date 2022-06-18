<?php

namespace Database\Seeders;

use App\Models\BidOrderStatus;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();
        $this->call([
            LaratrustSeeder::class,
            RiceSeeder::class,
            CornSeeder::class,
            CoconutSeeder::class,
            //SugarcaneSeeder::class,
            BananaSeeder::class,
            PineappleSeeder::class,
            MangoSeeder::class,
            //VegetableSeeder::class,
            ProjectStatusSeeder::class,
            BidOrderStatusSeeder::class
        ]);
    }
}
