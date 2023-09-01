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
        Schema::create('tb_city', function (Blueprint $table) {
            $table->id();

            $table->integer('code')
                ->comment('Code number of the City');

            $table->string('name')
                ->comment('Name of the City');

            $table->unsignedBigInteger('state_id');

            $table->foreign('state_id')->references('id')->on('tb_state');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tb_city');
    }
};
