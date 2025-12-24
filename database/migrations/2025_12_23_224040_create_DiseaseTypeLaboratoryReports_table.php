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
        Schema::create('DiseaseTypeLaboratoryReports', function (Blueprint $table) {
            $table->integer('LaboratoryReportId');
            $table->integer('DiseaseTypeId');
            $table->dateTime('CreatedDate', 7)->nullable();
            $table->uuid('CreatedBy');
            $table->dateTime('ModifiedDate', 7)->nullable();
            $table->uuid('ModifiedBy');
            $table->dateTime('DeletedDate', 7)->nullable();
            $table->uuid('DeletedBy');
            $table->boolean('IsDeleted');
            $table->boolean('IsSpecial')->default(false);

            $table->primary(['DiseaseTypeId', 'LaboratoryReportId'], 'pk_diseasetypelaboratoryreports');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('DiseaseTypeLaboratoryReports');
    }
};
