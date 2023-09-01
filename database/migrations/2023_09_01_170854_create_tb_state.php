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
        Schema::create('tb_state', function (Blueprint $table) {
            $table->id();

            $table->integer('code_uf')
                ->comment('Code number of the state');

            $table->string('name', 80)
                ->comment('State name');

            $table->string('state_code', 3)
                ->comment('Initials of the State');

            $table->unsignedBigInteger('country_id');

            $table->foreign('country_id')->references('id')->on('tb_country');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tb_state');
    }
};
