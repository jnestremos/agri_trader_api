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
        Schema::create('produce_inventories', function (Blueprint $table) {
            $table->foreignId('produce_yield_id')->constrained('produce_yields')->onUpdate('cascade')->onDelete('cascade');
            $table->double('produce_inventory_qtyOnHand');
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
        Schema::dropIfExists('produce_inventories');
    }
};
