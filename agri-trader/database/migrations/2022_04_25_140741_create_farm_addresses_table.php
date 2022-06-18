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
        Schema::create('farm_addresses', function (Blueprint $table) {
            $table->foreignId('farm_id')->constrained('farms')->onUpdate('cascade')->onDelete('cascade');
            $table->string('farm_province');
            $table->string('farm_address');
            $table->string('farm_city');
            $table->string('farm_zipcode');
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
        Schema::dropIfExists('farm_addresses');
    }
};
