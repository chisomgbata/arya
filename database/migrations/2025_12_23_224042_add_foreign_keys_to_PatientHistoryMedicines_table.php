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
        Schema::table('PatientHistoryMedicines', function (Blueprint $table) {
            $table->foreign(['MedicineId'], 'FK_PatientHistoryMedicines_Medicines_MedicineId')->references(['Id'])->on('Medicines')->onUpdate('no action')->onDelete('cascade');
            $table->foreign(['PatientHistoryId'], 'FK_PatientHistoryMedicines_PatientHistories_PatientHistoryId')->references(['Id'])->on('PatientHistories')->onUpdate('no action')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('PatientHistoryMedicines', function (Blueprint $table) {
            $table->dropForeign('FK_PatientHistoryMedicines_Medicines_MedicineId');
            $table->dropForeign('FK_PatientHistoryMedicines_PatientHistories_PatientHistoryId');
        });
    }
};
