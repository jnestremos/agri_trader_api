<?php

namespace Database\Seeders;

use App\Models\BidOrderStatus;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BidOrderStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $statuses = [
            'For Approval',
            'To Be Confirmed by Distributor',
            'First Payment Pending',
            'First Payment Paid',
            'To Be Delivered',
            'Confirm Final Payment',
            'Request for Refund Pending',
            'Confirmation for Refund'
        ];

        foreach ($statuses as $status) {
            BidOrderStatus::create([
                'bid_order_status' => $status
            ]);
        }
    }
}
