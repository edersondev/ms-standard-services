<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('ta_state_region', function (Blueprint $table) {
            $table->unsignedBigInteger('state_id')->index();
            $table->foreign('state_id')->references('id')->on('tb_state')->onDelete('cascade');

            $table->unsignedBigInteger('region_id')->index();
            $table->foreign('region_id')->references('id')->on('tb_region')->onDelete('cascade');

            $table->primary(['state_id', 'region_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ta_state_region');
    }
};
