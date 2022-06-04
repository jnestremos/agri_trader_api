<?php

namespace Database\Seeders;

use App\Models\Produce;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SugarcaneSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        include_once("simple_html_dom.php");

        $html = file_get_html("https://www.sra.gov.ph/sugarcane-varieties/");

        foreach ($html->find('div[class="project-title"]') as $item) {
            Produce::create([
                'prod_name' => $item->plaintext,
                'prod_timeOfHarvest' => '90-120 days',
                'prod_type' => 'Sugarcane'
            ]);
        }
    }
}
