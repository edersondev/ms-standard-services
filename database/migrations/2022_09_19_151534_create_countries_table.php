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
        Schema::create('tb_country', function (Blueprint $table) {
            $table->id();

            $table->string('name', 80)
                ->comment('Country name');

            $table->string('iso_code', 2)
                ->comment('Alpha-2 codes are two-letter country codes defined in ISO 3166-1');

            $table->string('iso_code3', 3)
                ->nullable()
                ->comment('Alpha-3 codes are three-letter country codes defined in ISO 3166-1');

            $table->smallInteger('number_code')
                ->nullable()
                ->comment('Numeric (or numeric-3) codes are three-digit country codes defined in ISO 3166-1');

            $table->integer('dial')
                ->comment('International telephone dialing codes');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tb_country');
    }
};
