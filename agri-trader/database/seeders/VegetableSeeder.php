<?php

namespace Database\Seeders;

use App\Models\Produce;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class VegetableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        include_once("simple_html_dom.php");

        $html = file_get_html("https://www.wakalan.com/business-finance/top-10-most-profitable-vegetables-to-grow-in-the-philippines/");

        $result = $html->find('div[class="entry-content"]', 0);
        for ($i = 0; $i < count($result->getElementsByTagName('h3')); $i++) {
            if (str_contains($result->getElementsByTagName('h3')[$i]->plaintext, ", ")) {
                $items = explode(',', $result->getElementsByTagName('h3')[$i]->plaintext);
                $time = ['60-70 days', '100-120 days', '60-70 days'];
                for ($ii = 0; $ii < count($items); $ii++) {
                    if ($ii == 0) {
                        $item = explode('.', trim($items[$ii]));
                        Produce::create([
                            'prod_name' => trim($item[1]),
                            'prod_timeOfHarvest' => $time[$ii],
                            'prod_type' => 'Vegetable'
                        ]);
                    } else {
                        Produce::create([
                            'prod_name' => trim($items[$ii]),
                            'prod_timeOfHarvest' => $time[$ii],
                            'prod_type' => 'Vegetable'
                        ]);
                    }
                }
            } else {
                $time = ['90-120 days', '50-55 days', '60-90 days', '70-80 days', '80-90 days', '50-100 days', '50-56 days', '80-180 days', '60-85 days'];
                $name = explode('.', $result->getElementsByTagName('h3')[$i]->plaintext);
                Produce::create([
                    'prod_name' => trim($name[1]),
                    'prod_timeOfHarvest' => $time[$i - 1],
                    'prod_type' => 'Vegetable'
                ]);
            }
        }
    }
}
