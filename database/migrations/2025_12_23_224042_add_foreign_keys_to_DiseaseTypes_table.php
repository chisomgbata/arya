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
        Schema::table('DiseaseTypes', function (Blueprint $table) {
            $table->foreign(['DiseaseId'], 'FK_DiseaseTypes_Diseases_DiseaseId')->references(['Id'])->on('Diseases')->onUpdate('no action')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('DiseaseTypes', function (Blueprint $table) {
            $table->dropForeign('FK_DiseaseTypes_Diseases_DiseaseId');
        });
    }
};
