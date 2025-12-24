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
        Schema::table('DiseaseTypeMedicines', function (Blueprint $table) {
            $table->foreign(['AnupanaId'], 'FK_DiseaseTypeMedicines_Anupanas_AnupanaId')->references(['Id'])->on('Anupanas')->onUpdate('no action')->onDelete('cascade');
            $table->foreign(['DiseaseTypeId'], 'FK_DiseaseTypeMedicines_DiseaseTypes_DiseaseTypeId')->references(['Id'])->on('DiseaseTypes')->onUpdate('no action')->onDelete('cascade');
            $table->foreign(['MedicineId'], 'FK_DiseaseTypeMedicines_Medicines_MedicineId')->references(['Id'])->on('Medicines')->onUpdate('no action')->onDelete('cascade');
            $table->foreign(['TimeOfAdministrationId'], 'FK_DiseaseTypeMedicines_TimeOfAdministrations_TimeOfAdministrationId')->references(['Id'])->on('TimeOfAdministrations')->onUpdate('no action')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('DiseaseTypeMedicines', function (Blueprint $table) {
            $table->dropForeign('FK_DiseaseTypeMedicines_Anupanas_AnupanaId');
            $table->dropForeign('FK_DiseaseTypeMedicines_DiseaseTypes_DiseaseTypeId');
            $table->dropForeign('FK_DiseaseTypeMedicines_Medicines_MedicineId');
            $table->dropForeign('FK_DiseaseTypeMedicines_TimeOfAdministrations_TimeOfAdministrationId');
        });
    }
};
