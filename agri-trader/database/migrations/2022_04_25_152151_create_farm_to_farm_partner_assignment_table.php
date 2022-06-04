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
        Schema::create('farm_to_farm_partner_assignment', function (Blueprint $table) {
            $table->foreignId('farm_id')->constrained('farms')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('farm_partner_id')->constrained('farm_partners')->onUpdate('cascade')->onDelete('cascade');
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
        Schema::dropIfExists('farm_owner_partner_assignment');
    }
};
