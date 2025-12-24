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
        Schema::table('DiseaseTypeLaboratoryReports', function (Blueprint $table) {
            $table->foreign(['DiseaseTypeId'], 'FK_DiseaseTypeLaboratoryReports_DiseaseTypes_DiseaseTypeId')->references(['Id'])->on('DiseaseTypes')->onUpdate('no action')->onDelete('cascade');
            $table->foreign(['LaboratoryReportId'], 'FK_DiseaseTypeLaboratoryReports_LaboratoryReports_LaboratoryReportId')->references(['Id'])->on('LaboratoryReports')->onUpdate('no action')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('DiseaseTypeLaboratoryReports', function (Blueprint $table) {
            $table->dropForeign('FK_DiseaseTypeLaboratoryReports_DiseaseTypes_DiseaseTypeId');
            $table->dropForeign('FK_DiseaseTypeLaboratoryReports_LaboratoryReports_LaboratoryReportId');
        });
    }
};
