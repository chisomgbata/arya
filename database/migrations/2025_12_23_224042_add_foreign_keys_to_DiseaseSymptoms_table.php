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
        Schema::table('DiseaseSymptoms', function (Blueprint $table) {
            $table->foreign(['DiseaseId'], 'FK_DiseaseSymptoms_Diseases_DiseaseId')->references(['Id'])->on('Diseases')->onUpdate('no action')->onDelete('cascade');
            $table->foreign(['SymptomId'], 'FK_DiseaseSymptoms_Symptoms_SymptomId')->references(['Id'])->on('Symptoms')->onUpdate('no action')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('DiseaseSymptoms', function (Blueprint $table) {
            $table->dropForeign('FK_DiseaseSymptoms_Diseases_DiseaseId');
            $table->dropForeign('FK_DiseaseSymptoms_Symptoms_SymptomId');
        });
    }
};
