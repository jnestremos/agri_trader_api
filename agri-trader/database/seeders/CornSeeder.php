<?php

namespace Database\Seeders;

use App\Models\Produce;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CornSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        include_once("simple_html_dom.php");

        $html = file_get_html("https://pepper.ph/philippine-corn-guide/");

        for ($i = 0; $i < count($html->find('h2')); $i++) {
            if ($i >= 6 && $i <= 11) {
                Produce::create([
                    'prod_name' => $html->find('h2')[$i]->plaintext,
                    'prod_timeOfHarvest' => '60-90 days',
                    'prod_type' => 'Corn'
                ]);
            }
        }
    }
}
