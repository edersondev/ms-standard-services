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
        Schema::create('tb_region', function (Blueprint $table) {
            $table->id();

            $table->string('name', 80)
                ->comment('Name of the Region');

            $table->string('region_code', 3)
                ->nullable()
                ->comment('Initials of the Region');

            $table->unsignedBigInteger('country_id');

            $table->foreign('country_id')->references('id')->on('tb_country');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tb_region');
    }
};
