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
        Schema::create('distributor_addresses', function (Blueprint $table) {
            $table->foreignId('distributor_id')->constrained('distributors')->onUpdate('cascade')->onDelete('cascade');
            $table->string('distributor_province');
            $table->string('distributor_address');
            $table->string('distributor_zipcode');
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
        Schema::dropIfExists('distributor_addresses');
    }
};
