<?php

namespace Database\Seeders;

use App\Models\ProjectStatus;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProjectStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $statuses = ['For Approval', 'Cancelled', 'Approved', 'Terminated Successfully', 'Terminated w/ Complications'];

        foreach ($statuses as $status) {
            ProjectStatus::create([
                'project_status' => $status
            ]);
        }
    }
}
