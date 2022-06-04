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
        Schema::create('trader_addresses', function (Blueprint $table) {
            $table->foreignId('trader_id')->constrained('traders')->onUpdate('cascade')->onDelete('cascade');
            $table->string('trader_province');
            $table->string('trader_address');
            $table->string('trader_zipcode');
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
        Schema::dropIfExists('trader_addresses');
    }
};
