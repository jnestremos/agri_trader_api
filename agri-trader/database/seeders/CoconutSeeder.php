<?php

namespace Database\Seeders;

use App\Models\Produce;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CoconutSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        include_once("simple_html_dom.php");

        $html = file_get_html("https://businessdiary.com.ph/3062/coconut-production-guide/");

        $table = $html->find('table', 0);
        $tbody = $table->find('tbody', 0)->children();
        for ($i = 0; $i < count($tbody); $i++) {
            if ($i != 0 && $i != 1 && $i != 21 && $tbody[$i]->children()[2]->plaintext == "Philippines") {
                Produce::create([
                    'prod_name' => $tbody[$i]->children()[0]->plaintext,
                    'prod_timeOfHarvest' => '75-90 days',
                    'prod_type' => 'Coconut'
                ]);
            }
        }
    }
}
