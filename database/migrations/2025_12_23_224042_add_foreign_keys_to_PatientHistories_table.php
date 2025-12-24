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
        Schema::table('PatientHistories', function (Blueprint $table) {
            $table->foreign(['PatientId'], 'FK_PatientHistories_Patients_PatientId')->references(['Id'])->on('Patients')->onUpdate('no action')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('PatientHistories', function (Blueprint $table) {
            $table->dropForeign('FK_PatientHistories_Patients_PatientId');
        });
    }
};
