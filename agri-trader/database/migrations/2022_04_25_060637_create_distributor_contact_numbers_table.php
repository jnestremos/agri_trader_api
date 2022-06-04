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
        Schema::create('distributor_contact_numbers', function (Blueprint $table) {
            $table->foreignId('distributor_id')->constrained('distributors')->onUpdate('cascade')->onDelete('cascade');
            $table->string('distributor_contactNum');
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
        Schema::dropIfExists('distributor_contact_numbers');
    }
};
