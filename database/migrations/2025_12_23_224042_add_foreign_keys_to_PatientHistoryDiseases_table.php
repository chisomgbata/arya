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
        Schema::table('PatientHistoryDiseases', function (Blueprint $table) {
            $table->foreign(['DiseaseId'], 'FK_PatientHistoryDiseases_Diseases_DiseaseId')->references(['Id'])->on('Diseases')->onUpdate('no action')->onDelete('cascade');
            $table->foreign(['DiseaseTypeId'], 'FK_PatientHistoryDiseases_DiseaseTypes_DiseaseTypeId')->references(['Id'])->on('DiseaseTypes')->onUpdate('no action')->onDelete('no action');
            $table->foreign(['PatientHistoryId'], 'FK_PatientHistoryDiseases_PatientHistories_PatientHistoryId')->references(['Id'])->on('PatientHistories')->onUpdate('no action')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('PatientHistoryDiseases', function (Blueprint $table) {
            $table->dropForeign('FK_PatientHistoryDiseases_Diseases_DiseaseId');
            $table->dropForeign('FK_PatientHistoryDiseases_DiseaseTypes_DiseaseTypeId');
            $table->dropForeign('FK_PatientHistoryDiseases_PatientHistories_PatientHistoryId');
        });
    }
};
