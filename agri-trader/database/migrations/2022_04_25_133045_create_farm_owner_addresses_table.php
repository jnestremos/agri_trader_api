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
        Schema::create('farm_owner_addresses', function (Blueprint $table) {
            $table->foreignId('farm_owner_id')->constrained('farm_owners')->onUpdate('cascade')->onDelete('cascade');
            $table->string('owner_province');
            $table->string('owner_address');
            $table->string('owner_zipcode');
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
        Schema::dropIfExists('farm_owner_addresses');
    }
};
