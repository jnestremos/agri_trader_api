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
        Schema::create('bid_orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('trader_id')->constrained('traders')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('distributor_id')->constrained('distributors')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('project_id')->constrained('projects')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('bid_order_status_id')->constrained('bid_order_statuses')->onUpdate('cascade')->onDelete('cascade');
            $table->char('order_grade')->nullable();
            $table->date('order_dateNeededTo');
            $table->date('order_dateNeededFrom');
            $table->double('order_initialPrice');
            $table->double('order_negotiatedPrice')->nullable();
            $table->double('order_finalQty')->nullable();
            $table->double('order_finalPrice')->nullable();
            $table->double('order_finalTotal')->nullable();
            $table->date('order_dpDueDate')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bid_orders');
    }
};
