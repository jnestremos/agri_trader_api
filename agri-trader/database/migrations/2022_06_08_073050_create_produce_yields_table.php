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
        Schema::create('produce_yields', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained('projects')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('produce_id')->constrained('produces')->onUpdate('cascade')->onDelete('cascade');
            $table->char('produce_yield_class');
            $table->double('produce_yield_qtyHarvested');
            $table->integer('produce_yield_price');
            $table->string('produce_yield_unit')->default('kgs');
            $table->date('produce_yield_dateHarvestFrom');
            $table->date('produce_yield_dateHarvestTo');
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
        Schema::dropIfExists('produce_yields');
    }
};
