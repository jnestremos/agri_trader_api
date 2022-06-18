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
        Schema::create('sales', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bid_order_id')->constrained('bid_orders')->onUpdate('cascade')->onDelete('cascade');
            $table->string('sale_type');
            $table->double('sale_qty');
            $table->double('sale_stockLeft');
            $table->double('sale_price');
            $table->double('sale_total');
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
        Schema::dropIfExists('sales');
    }
};
