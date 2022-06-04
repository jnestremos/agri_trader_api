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
        Schema::create('farm_partner_addresses', function (Blueprint $table) {
            $table->foreignId('farm_partner_id')->constrained('farm_partners')->onUpdate('cascade')->onDelete('cascade');
            $table->string('partner_province');
            $table->string('partner_address');
            $table->string('partner_zipcode');
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
        Schema::dropIfExists('farm_partner_addresses');
    }
};
