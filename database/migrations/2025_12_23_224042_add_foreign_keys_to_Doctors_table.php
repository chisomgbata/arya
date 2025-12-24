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
        Schema::table('Doctors', function (Blueprint $table) {
            $table->foreign(['CityId'], 'FK_Doctors_Cities_CityId')->references(['Id'])->on('Cities')->onUpdate('no action')->onDelete('no action');
            $table->foreign(['StateId'], 'FK_Doctors_States_StateId')->references(['Id'])->on('States')->onUpdate('no action')->onDelete('no action');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('Doctors', function (Blueprint $table) {
            $table->dropForeign('FK_Doctors_Cities_CityId');
            $table->dropForeign('FK_Doctors_States_StateId');
        });
    }
};
