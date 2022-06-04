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
        Schema::create('farm_partners', function (Blueprint $table) {
            $table->id();
            $table->foreignId('trader_id')->constrained('traders')->onUpdate('cascade')->onDelete('cascade');
            $table->string('partner_firstName');
            $table->string('partner_lastName');
            $table->date('partner_birthDate');
            $table->boolean('partner_status')->default(true);
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
        Schema::dropIfExists('farm_partners');
    }
};
