<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('on_hand_bids', function (Blueprint $table) {
            $table->foreignId('bid_order_id')->constrained('bid_orders')->onUpdate('cascade')->onDelete('cascade');
            $table->double('on_hand_bid_qty');
            $table->double('on_hand_bid_total');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('on_hand_bids');
    }
};
