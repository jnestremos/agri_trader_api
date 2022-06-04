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
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->foreignId('contract_id')->constrained('contracts')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('project_status_id')->constrained('project_statuses')->onUpdate('cascade')->onDelete('cascade');
            $table->date('project_completionDate');
            $table->date('project_commenceDate');
            $table->date('project_floweringStart');
            $table->date('project_floweringEnd');
            $table->date('project_fruitBuddingStart');
            $table->date('project_fruitBuddingEnd');
            $table->date('project_devFruitStart');
            $table->date('project_devFruitEnd');
            $table->date('project_harvestableStart');
            $table->date('project_harvestableEnd');
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
        Schema::dropIfExists('projects');
    }
};
