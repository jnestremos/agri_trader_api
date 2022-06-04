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
        Schema::create('bid_order_accounts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bid_order_id')->constrained('bid_orders')->onUpdate('cascade')->onDelete('cascade');
            $table->string('bid_order_acc_type');
            $table->string('bid_order_acc_paymentMethod');
            $table->string('bid_order_acc_bankName')->nullable();
            $table->string('bid_order_acc_accNum')->nullable();
            $table->string('bid_order_acc_accName')->nullable();
            $table->string('bid_order_acc_amount');
            $table->string('bid_order_acc_remarks')->nullable();
            $table->date('bid_order_acc_datePaid');
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
        Schema::dropIfExists('bid_order_accounts');
    }
};
